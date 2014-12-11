<script type="text/javascript">
    var BASE_URL = '<?=base_url()?>';
    var WIKI = '<?=WIKI?>';
    var PATH_COOKIE = '<?=PATH_COOKIE?>';
    var IMG = '<?=IMG?>';
    var JS = '<?=JS?>';
</script>
<link href='<?=CSS;?>/<?=(@$_COOKIE['tema'] == '' ? 'redmond' : @$_COOKIE['tema']);?>/all.css.jquery-ui.10092013134409977101.css' type='text/css' rel='stylesheet'/>
<link rel='shortcut icon' href='<?=IMG;?>/favicon.ico' type='image/ico'/>
<? if (PRODUCAO) { ?>
<script type='text/javascript' src='<?=JS;?>/all.javascript.10092013134409977101.js'></script>
<? } else { ?>
    <script type='text/javascript' src='<?=JS;?>/excanvas.min.js'></script>
    <script type='text/javascript' src='<?=JS;?>/jquery.js'></script>
    <script type='text/javascript' src='<?=JS;?>/jquery-ui.js'></script>
    <script type='text/javascript' src='<?=JS;?>/jquery.easing.min.js'></script>
    <script type='text/javascript' src='<?=JS;?>/jquery.jBreadCrumb.1.1.js'></script>
    <script type='text/javascript' src='<?=JS;?>/jquery.ui.selectmenu.js'></script>
    <script type='text/javascript' src='<?=JS;?>/jquery.form.js'></script>
    <script type='text/javascript' src='<?=JS;?>/jquery.ui.datepicker-pt-BR.js'></script>
    <script type='text/javascript' src='<?=JS;?>/i18n/grid.locale-pt-br.js'></script>
    <script type='text/javascript' src='<?=JS;?>/jquery.jqGrid.min.js'></script>
    <script type='text/javascript' src='<?=JS;?>/jquery.tablednd.js'></script>
    <script type='text/javascript' src='<?=JS;?>/jquery.cookie.min.js'></script>
    <script type='text/javascript' src='<?=JS;?>/jquery.treeview.1.5pre.js'></script>
    <script type='text/javascript' src='<?=JS;?>/jquery.highlight-3.js'></script>
    <script type='text/javascript' src='<?=JS;?>/webtoolkit.utf8.js'></script>
    <script type='text/javascript' src='<?=JS;?>/jquery.maskedinput.min.js'></script>
    <script type='text/javascript' src='<?=JS;?>/masks.js'></script>
    <script type='text/javascript' src='<?=JS;?>/jquery.maskMoney.js'></script>
    <script type='text/javascript' src='<?=JS;?>/jquery.decimalMask.js'></script>
    <script type='text/javascript' src='<?=JS;?>/webcam.js'></script>
    <script type='text/javascript' src='<?=JS;?>/jquery.fixFloat.js'></script>
    <script type='text/javascript' src='<?=JS;?>/functions.grid.js'></script>
    <script type='text/javascript' src='<?=JS;?>/functions.window.js'></script>
    <script type='text/javascript' src='<?=JS;?>/jquery.jqplot.js'></script>
    <script type='text/javascript' src='<?=JS;?>/require.js'></script>
    <script type='text/javascript' src='<?=JS;?>/functions.js'></script>
<? } ?>
