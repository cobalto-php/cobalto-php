<?= headerView() ?>

<?=path_bread($path_bread);?>

<?= begin_ToolBar(['abrir', 'salvar', 'imprimir', 'ajuda']); ?>
<?= end_ToolBar(); ?>

<?= begin_TabPanel('tabFiltroTurma'); ?>
    <?= begin_Tab('Aluno'); ?>

        <?= form_label('lblCodMatricula', 'Código de matrícula', 120); ?>
        <?= form_textField('txtCodMatricula', '', 100, '', 255); ?>
        <?= new_line(); ?>

        <?= form_label('lblNome', 'Nome', 120); ?>
        <?= form_textField('txtNome', '', 240) ?>
        <?= new_line(); ?>

        <?= form_label('lblCpf', 'CPF', 120); ?>
        <?=form_textField('txtCpf', @$pessoa->cpf, 100, 'cpf');?>

    <?= end_Tab(); ?>
<?= end_TabPanel(); ?>

<?= begin_JqGridPanel('gridAluno', 'auto', '', base_url().'autenticaca/aluno/listaAluno/', ['autoload' => FALSE, 'sortname' => 'cod_atividade, periodo, cod_turma', 'autowidth' => TRUE, 'rowNum' => 25, 'pager' => TRUE, 'caption' => 'Lista alunos']) ?>
    <?= addJqGridColumn('id', 'ID', 0, 'right', ['sortable' => TRUE, 'hidden' => TRUE]); ?>
    <?= addJqGridColumn('cod_matricula', 'Cod matrícula', 10, 'left', ['sortable' => TRUE]); ?>
    <?= addJqGridColumn('nome', 'Nome', 5, 'left', ['sortable' => TRUE]); ?>
    <?= addJqGridColumn('atividade_curricular', lang('turmaAtividadeCurricular'), 45, 'left', ['sortable' => TRUE]); ?>
    <?= addJqGridColumn('cursos', 'Cursos', 35, 'left', ['sortable' => TRUE]); ?>
    <?= addJqGridColumn('periodo', 'Período', 5, 'center', ['sortable' => TRUE]); ?>
<?= end_JqGridPanel(); ?>

<?//= loadJavaScript('academico/assets/cadastros/ofertaCraFiltroView.js', NULL, $_ci_vars); ?>

<?= footerView() ?>
