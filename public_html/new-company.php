<?php
require_once(__DIR__.'/../inc/autoloader.inc.php');
$cfg = new SpringSys\Config();
$tpl = new SpringSys\Template($cfg);

if (!empty($_POST)){
    $comp = new \SpringSys\Company(new SpringSys\Db($cfg));
    $errors = '';
    if (!empty($_POST['hp_name'])){
        //the honey pot field should always be empty, if a bot has filled it in, we know it is spam
        //throw new \Exception("An unknown error has occurred");
        //fail nicely
        $errors = 'An unknown error has occurred.';
    }else {
        if (empty($_POST['name'])) {
            if (!empty($errors)) {
                $errors .= "<br>\n";
            }
            $errors .= "Name is empty";
        } else {
            $companyname = htmlentities($_POST['name']);
        }
        if (empty($_POST['street1'])) {
            if (!empty($errors)) {
                $errors .= "<br>\n";
            }
            $errors .= "Street 1 is empty";
        } else {
            $companystreet1 = htmlentities($_POST['street1']);
        }
        if (isset($_POST['street2'])) {
            $companystreet2 = htmlentities($_POST['street2']);
        }
        if (empty($_POST['city'])) {
            if (!empty($errors)) {
                $errors .= "<br>\n";
            }
            $errors .= "City is empty";
        } else {
            $companycity = htmlentities($_POST['city']);
        }
        if (empty($_POST['state_province'])) {
            if (!empty($errors)) {
                $errors .= "<br>\n";
            }
            $errors .= "State/Province is empty";
        } else {
            $companystate_province = htmlentities($_POST['state_province']);
        }
        if (empty($_POST['zip'])) {
            if (!empty($errors)) {
                $errors .= "<br>\n";
            }
            $errors .= "Postal Code/Zip is empty";
        } else {
            $companyzip = htmlentities($_POST['zip']);
        }
    }
    if (!empty($errors)){
        $tpl->setError("There are errors with your submission: <br>\n".$errors);
    }else{
        if (empty($_POST['employee_first'])){
            $tpl->setError("You need at least 1 employee in the company");
        }else{
            try {
                $newcompanyid = $comp->add($_POST['name'], $_POST['street1'], $_POST['street2'],
                    $_POST['city'], $_POST['state_province'], $_POST['zip']);

                if ($newcompanyid === -1){
                    $tpl->setError('There was an error adding the company.');
                }else{
                    $validtries = 0;
                    $failcount = 0;
                    $successcount = 0;
                    foreach($_POST['employee_first'] as $i => $firstname){
                        if ($i === 0){
                            //ignore 0th element because that is the template
                            continue;
                        }
                        if (!isset($_POST['employee_middle'][$i])){
                            continue;
                            //throw new \Exception("Middle name not sent");
                        }
                        if (!isset($_POST['employee_last'][$i])){
                            continue;
                            //throw new \Exception("Last name not sent");
                        }
                        $validtries++;
                        //if we got here, a full employee name exists in post
                        $addedempid = $comp->addEmployee($newcompanyid, $firstname, $_POST['employee_middle'][$i], $_POST['employee_last'][$i]);
                        if ($addedempid === -1){
                            $failcount++;
                        }else{
                            $successcount++;
                        }
                    }
                    if ($successcount > 0){
                        $tpl->setSuccess("You have saved a company with ".$successcount.' employee(s).');
                    }
                    if ($failcount){
                        $tpl->setError('There were '.$failcount.' employee(s) that could not be added.');
                    }
                }

            }catch(Throwable $e){
                $newcompanyid = -1;
                $tpl->setError($e->getMessage());
            }

        }

        $tpl->setSuccess('Company added!');
    }
}//end if accepting a POST



$title = 'New Company';
$tpl->header($title);
?>
<form method="post">
    <?php
    //This is the honey pot to stop spammers.
    //What we will do is allow there bots to fill out the hp_name field, because they can't read css very well
    //if they fill it in, we know it is a bot/spammer.
    ?>
    <div class="hp">
    <input type="text" name="hp_name" value="">
    </div>

    <div class="tbl">
        <div class="tblrow">
            <div class="tblcell">
                <label for="companyname">Company Name:</label>
            </div>
            <div class="tblcell">
                <input id="companyname" type="text" name="name" value="<?=$companyname ?? ''?>">
            </div>
        </div>
        <div class="tblrow">
            <div class="tblcell">
                <label for="companystreet1">Street 1: </label>
            </div>
            <div class="tblcell">
                <input id="companystreet1" type="text" name="street1" value="<?=$companystreet1 ?? ''?>">
            </div>
        </div>
        <div class="tblrow">
            <div class="tblcell">
                <label for="companystreet2">Street 2: </label>
            </div>
            <div class="tblcell">
                <input id="companystreet2" type="text" name="street2" value="<?=$companystreet2 ?? ''?>">
            </div>
        </div>
        <div class="tblrow">
            <div class="tblcell">
                <label for="companycity">City: </label>
            </div>
            <div class="tblcell">
                <input id="companycity" type="text" name="city" value="<?=$companycity ?? ''?>">
            </div>
        </div>
        <div class="tblrow">
            <div class="tblcell">
                <label for="companystateprovince">State/Province: </label>
            </div>
            <div class="tblcell">
                <input id="companystateprovince" type="text" name="state_province" value="<?=$companystate_province ?? ''?>">
            </div>
        </div>

        <div class="tblrow">
            <div class="tblcell">
                <label for="companyzip">Postal Code (Zip): </label>
            </div>
            <div class="tblcell">
                <input id="companyzip" type="text" name="zip" value="<?=$companyzip ?? ''?>">
            </div>
        </div>
    </div>
    <h2>Employees</h2>
    <nav class="employee_links">
        <a href="#addemployee" onclick="addEmployee(); return false" title="Add Employee">Add Employee <img src="<?=$tpl->cfg->get('baseurl')?>/images/387516_+_add_character_increase_math_icon.svg"></a>
    </nav>
    <div class="employees">
        <div class="hidden template">
            Employee: <label>First <input class="first" type="text" name="employee_first[0]" value=""></label>
            <label>Middle <input class="middle" type="text" name="employee_middle[0]" value=""></label>
            <label>Last <input class="last" type="text" name="employee_last[0]" value=""></label>
            <a href="#removeemployee" title="Remove Employee" class="rem_employee"><img src ="<?=$tpl->cfg->get('baseurl')?>/images/211862_minus_circled_icon.svg"></a>
        </div>
    </div>
    <button type="submit">Save</button>
</form>
<script>
    function getLastId(){
        var max_id = -1;
        $('.employees .employee').each(function(){
            var name = $(this).find('.first').attr('name');
            var myregexp = /\[(\d+)\]/;
            var match = myregexp.exec(name);
            var result = -1;
            if (match != null && match[1] !== undefined) {
                result = parseInt(match[1]);
                if (result > max_id){
                    max_id = result;
                }
            } else {
                result = -1;
            }
        });
        return max_id;
    }

    function addEmployee(){
        let tpl = $('.employees .template').html() || '';
        let max = getLastId();
        let id;
        if (max <= 0){
            id = 1;
        }else{
            id = max+1;
        }
        let emp = tpl.replace('employee_first[0]','employee_first['+id+']')
            .replace('employee_middle[0]','employee_middle['+id+']')
            .replace('employee_last[0]','employee_last['+id+']');

        $('.employees').append($('<div class="employee"></div>').html(emp));
    }

    $(document).on("click", "a.rem_employee", function(){
        $(this).closest('.employee').remove();
    });
</script>
<?php
$tpl->footer();
