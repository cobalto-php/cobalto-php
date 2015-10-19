<?php

trait CobaltoTrait
{
    private static $default_rows = 10;
    private static $default_page = 1;

    public function getError(){
        $erros = $this->errors->get_raw_errors();
        return array("field" => key($erros), "message" => $erros[key($erros)][0]);
    }

    public function set_attributes($data=array(), $except=array()){
        $filtered_data = array();
        $attributes = array_values(array_intersect(array_keys($this->attributes()), array_keys($data)));
        $attributes = array_diff($attributes, array_merge($except, array('dt_cadastro', 'dt_alteracao', 'id', 'usuario_id', 'flg_deletado')));

        foreach ($attributes as $attribute){
            $filtered_data[$attribute] = $data[$attribute] ? $data[$attribute] : NULL;
        }

        parent::set_attributes($filtered_data);
    }

    public function save_with_transaction($validate=true) {
        $class = get_called_class();
        $object = $this;
        return $class::transaction(function() use ($validate, $object) {
            $object->verify_not_readonly('save');
            return $object->is_new_record() ? $object->insert_with_transaction($validate) : $object->update_with_transaction($validate);
        }, false, $object);
    }

    private function insert_with_transaction($validate=true)
    {
        $this->verify_not_readonly('insert');

        if (($validate && !$this->_validate_with_transaction() || !$this->invoke_callback('before_create',false)))
            return false;

        $table = static::table();

        if (!($attributes = $this->dirty_attributes()))
            $attributes = $this->attributes;

        $pk = $this->get_primary_key(true);
        $use_sequence = false;

        if ($table->sequence && !isset($attributes[$pk]))
        {
            if (($conn = static::connection()) instanceof OciAdapter)
            {
                // terrible oracle makes us select the nextval first
                $attributes[$pk] = $conn->get_next_sequence_value($table->sequence);
                $table->insert($attributes);
                $this->attributes[$pk] = $attributes[$pk];
            }
            else
            {
                // unset pk that was set to null
                if (array_key_exists($pk,$attributes))
                    unset($attributes[$pk]);

                $table->insert($attributes,$pk,$table->sequence);
                $use_sequence = true;
            }
        }
        else
            $table->insert($attributes);

        // if we've got an autoincrementing/sequenced pk set it
        // don't need this check until the day comes that we decide to support composite pks
        // if (count($pk) == 1)
        {
            $column = $table->get_column_by_inflected_name($pk);

            if ($column->auto_increment || $use_sequence)
                $this->attributes[$pk] = static::connection()->insert_id($table->sequence);
        }

        $this->__new_record = false;
        if (!$this->invoke_callback('after_create',false))
        {
            $this->__new_record = true;
            return false;
        }

        return true;
    }

    private function update_with_transaction($validate=true)
    {
        $this->verify_not_readonly('update');

        if ($validate && !$this->_validate_with_transaction())
            return false;

        if ($this->is_dirty())
        {
            $pk = $this->values_for_pk();

            if (empty($pk))
                throw new ActiveRecordException("Cannot update, no primary key defined for: " . get_called_class());

            if (!$this->invoke_callback('before_update',false))
                return false;

            $dirty = $this->dirty_attributes();
            static::table()->update($dirty,$pk);
            if (!$this->invoke_callback('after_update',false))
                return false;
        }

        return true;
    }

    private function _validate_with_transaction()
    {
        $dir = __DIR__.'/../vendor/php-activerecord/php-activerecord/lib';
        require_once $dir.'/Validations.php';

        $validator = new ActiveRecord\Validations($this);
        $validation_on = 'validation_on_' . ($this->is_new_record() ? 'create' : 'update');

        $this->errors = $validator->get_record();

        foreach (array('before_validation', "before_$validation_on") as $callback)
        {
            if (!$this->invoke_callback($callback,false))
                return false;
        }

        // need to store reference b4 validating so that custom validators have access to add errors
        $validator->validate();

        foreach (array('after_validation', "after_$validation_on") as $callback)
        {
            if (!$this->invoke_callback($callback,false))
                return false;
        }

        if (!$this->errors->is_empty())
            return false;

        return true;
    }

    public static function transaction($closure, $throw=true, \ActiveRecord\Model $object)
    {
        $connection = static::connection();

        try
        {
            $connection->transaction();

            if ($closure() === false)
            {
                $connection->rollback();
                return false;
            }
            else
                $connection->commit();
        }
        catch (\Exception $e)
        {
            $connection->rollback();
            if ($throw)
            {
                throw $e;
            } else {
                try {
                    logVar($e->getMessage(), 'CobaltoTrait');
                    $object->errors->add('id', 'Não foi possível salvar o registro.');
                }
                catch (\Exception $e)
                {
                    logVar($e->getMessage(), 'CobaltoTrait');
                }
                return false;
            }

        }
        return true;
    }

    public static function sendToGrid($params=[])
    {
        $class = get_called_class();

        if(isset($params["group"])) {
            $qtd = count($class::find('all', $params));
        } else {
            $qtd = $class::count($params);
        }

        $paramsJqGrid = new stdClass();
        $paramsJqGrid->sortField = (isset($_GET['sidx']) ? $_GET['sidx'] : NULL);
        $paramsJqGrid->sortDirection = (isset($_GET['sord']) ? $_GET['sord'] : "asc");
        $paramsJqGrid->records = $qtd;

        $paramsJqGrid->limit    = isset($_GET['rows']) ? $_GET['rows'] : self::$default_rows;
        $paramsJqGrid->total    = $qtd > 0 ? ceil($qtd / $paramsJqGrid->limit) : 0;
        $paramsJqGrid->page     = isset($_GET['page']) ? $_GET['page'] : self::$default_page;
        $paramsJqGrid->start    = $paramsJqGrid->limit * $paramsJqGrid->page - $paramsJqGrid->limit;

        if ($paramsJqGrid->start < 0){
            $paramsJqGrid->start = 0;
        }

        if ($qtd > 0){
            if (!empty($paramsJqGrid->sortField)){
                $params = array_merge($params, ['order' => $paramsJqGrid->sortField.' '.$paramsJqGrid->sortDirection]);
            }
            if ($paramsJqGrid->limit >= 0){
                $params = array_merge($params, ['limit' => $paramsJqGrid->limit, 'offset' => $paramsJqGrid->start]);
            }

            $result = $class::find('all', $params);

            foreach ($result as $r){
                $paramsJqGrid->rows[] = $r->to_array();
            }
        }

        echo json_encode($paramsJqGrid);

        return TRUE;
    }
}
?>