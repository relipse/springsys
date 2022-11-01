<?php
/**
 * Companies page
 *  - show all companies with the number of employees
 *
 * Code Challenge
 * @author Jim A Kinsman <relipse@gmail.com>
 * @copyright 2022 Jim A Kinsman
 */
require_once(__DIR__.'/../inc/autoloader.inc.php');

$cfg = new \SpringSys\Config();
$db = new SpringSys\Db($cfg);
$tpl = new \SpringSys\Template($cfg);

if (!empty($_POST['frm'])){
    if ($_POST['frm'] === 'frmRemoveCompany'){
        if (!empty($_POST['company_id'])){
            $company_id = $_POST['company_id'];
            $comp = new \SpringSys\Company($db);
            $num_emps = $comp->countEmployees($company_id);

            if ($comp->remove($company_id)){
                $tpl->setSuccess('Company removed along with '.$num_emps.' employee(s).');
            }else{
                $tpl->setError('There was an error removing this company');
            }
        }else{
            $tpl->setError("Nothing submitted");
        }

    }else{
        $tpl->setError("Invalid form submission");
    }
}


$title = 'Companies';
$tpl->header($title);
?>
List of Companies with number of Employees
<?php
$comp = new \SpringSys\Company($db);

$companies = $comp->getAll();
foreach($companies as &$company){
    $company['action'] = '<a href="#remove_company" class="removeCompany" data-company-name="'.htmlentities($company['name']).'" data-company-id="'.$company['id'].'">Remove</a>';
}
echo array2table_allow_html($companies);
?>
<form name="frmRemoveCompany" method="post">
    <input type="hidden" name="frm" value="frmRemoveCompany">
    <input type="hidden" name="company_id" value="">
</form>
<script>
    $('.removeCompany').click(function(){
        let company = $(this).attr('data-company-name');
        if (confirm("Aare you sure you want to remove '" + company+"' and all of its employees?")){
            $('form input[name=company_id]').val($(this).attr('data-company-id'));
            $('form[name=frmRemoveCompany]').submit();
        }
        return false;
    })
</script>
<?php
$tpl->footer();
