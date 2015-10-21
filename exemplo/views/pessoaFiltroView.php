<?= headerView() ?>

<?=path_bread($path_bread);?>

<?= begin_ToolBar(["abrir", "salvar", "imprimir", "ajuda"]); ?>
<?= end_ToolBar(); ?>

<?= begin_TabPanel("tabFiltroTurma"); ?>
    <?= begin_Tab(lang("turmaFiltro")); ?>

        <?= form_label("lblAno", lang("turmaAno"), 120); ?>
        <?= form_textField("txtAno", getParametro("ANO_OFERTA_DEFAULT"), 40, "ano", 4) ?>
        <?= new_line(); ?>

        <?= form_label("lblBasePeriodo", lang("turmaBasePeriodo"), 120); ?>
        <?= form_combo("cmbTurmaBasePeriodo", isset($base_periodos) ? $base_periodos : "", getParametro("SEMESTRE_OFERTA_DEFAULT"), 123); ?>
        <?= new_line(); ?>

        <?= form_label("lblCodTurma", lang("Código da turma"), 120); ?>
        <?= form_textField("txtCodTurma", "", 100, "", 255, array("style" => "margin-right: 50px;")); ?>
        <?= new_line(); ?>

        <?= form_label("lblUnidade", "Unidade academica", 120); ?>
        <?= form_combo("cmbUnidade", isset($unidades) ? $unidades : "", "", 559); ?>
        <?= new_line(); ?>

        <?= form_label("lblAtividade", "Atividade curricular", 120); ?>
        <?= form_textFieldAutoComplete("atividade", base_url()."academico/cadastros/ofertaCra/listaAtividades/", "", "", 536); ?>
        <?= new_line(); ?>

        <?= form_label("lblNomeCurso", "Curso", 120); ?>
        <?= form_buttonHit("curso", base_url()."academico/cadastros/ofertaDepartamento/bthCursos/false", "Escolha o curso", 900); ?>

    <?= end_Tab(); ?>
<?= end_TabPanel(); ?>

<?= begin_JqGridPanel("gridTurma", "auto", "", base_url()."academico/cadastros/ofertaCra/listaTurmas/", ["autoload" => FALSE, "sortname" => "cod_atividade, periodo, cod_turma", "autowidth" => TRUE, "rowNum" => 25, "pager" => TRUE, "caption" => lang("turmaListaTurmas")]) ?>
    <?= addJqGridColumn("id", "ID", 0, "right", ["sortable" => TRUE, "hidden" => TRUE]); ?>
    <?= addJqGridColumn("cod_atividade", lang("turmaCodAtividade"), 10, "center", ["sortable" => TRUE]); ?>
    <?= addJqGridColumn("cod_turma", lang("turmaCodTurma"), 5, "center", ["sortable" => TRUE]); ?>
    <?= addJqGridColumn("atividade_curricular", lang("turmaAtividadeCurricular"), 45, "left", ["sortable" => TRUE]); ?>
    <?= addJqGridColumn("cursos", "Cursos", 35, "left", ["sortable" => TRUE]); ?>
    <?= addJqGridColumn("periodo", "Período", 5, "center", ["sortable" => TRUE]); ?>
<?= end_JqGridPanel(); ?>

<?= loadJavaScript("academico/assets/cadastros/ofertaCraFiltroView.js", NULL, $_ci_vars); ?>

<?= footerView() ?>
