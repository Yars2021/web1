<?php

$time = microtime(true);

//$filename = (getenv("HOME") ?? __DIR__) . DIRECTORY_SEPARATOR . "values.csv";
$filename = '/home/s312198/values.csv';

if (!file_exists($filename)) {
    $fh = fopen($filename, 'a');
    fclose($fh);
}

function add_record($filename, $x, $y, $r, $executionTime, $result)
{
    $fh = fopen($filename, 'a');
    fwrite($fh, "" . time() . ",$x,$y,$r,$executionTime,$result\n");
    fclose($fh);
}

function get_all_records($filename)
{
    $lines = array();
    $fh = fopen($filename, 'r');
    while (!feof($fh)) {
        $line = fgets($fh);
        $line = preg_replace("/[\r\n]/", "", $line);
        if ($line !== '') {
            $lines[] = $line;
        }
    }
    fclose($fh);
    rsort($lines);
    return $lines;
}

function get_fields($source)
{
    $values = explode(',', $source);
    $values[0] = date("Y-m-d H:i:s", $values[0]);
    return $values;
}

function calculate($x, $y, $r)
{
    if ((($x >= 0) && ($x <= $r) && ($y <= 0) && ($y >= -$r)) || 
        (($x < 0) && ($y < 0) && (($x + $y) >= (-$r / 2))) ||
        (($x < 0) && ($y >= 0) && (($x * $x + $y * $y) <= ($r * $r)))) {
        $result = 1;
    } else {
        $result = 0;
    }
    return $result;
}

$x = isset($_GET['x']) ? $_GET['x'] : null;
$y = isset($_GET['y']) ? $_GET['y'] : null;
$r = isset($_GET['r']) ? $_GET['r'] : null;

$action = isset($_GET['action']) ? $_GET['action'] : '';
$showAdded = false;

if (isset($action)) {
    switch ($action) {
        case 'check':
        {
            $showAdded = true;
        }
    }
}

if (is_numeric($x) && is_numeric($y) && is_numeric($r)) {
    $result = calculate($x, $y, $r);
    $executionTime = round((microtime(true) - $time) * 1000, 3);
    add_record($filename, $x, $y, $r, $executionTime, $result);
    $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http")
        . "://$_SERVER[HTTP_HOST]$_SERVER[PHP_SELF]" . '?action=check';
    header("Location: $actual_link");
    exit();
}

?>
<!DOCTYPE html>

<html lang="en">
    <head>
        <title class="Title">Point checker</title>
        <link href="styles/main.css" type="text/css" rel="stylesheet" />
        <script src="scripts/main.js" type="text/javascript"></script>
    </head>
    <body>
        <div class="MainBlock">
            <table class="MainTable">
                <tr class="MainRow">
                    <td colspan="2" class="NameCell">
                        <h1 class="NameHeader">Sukhovey Yaroslav</h1>
                    </td>
                </tr>
                <tr class="MainRow">
                    <td class="InfoCell">
                        <h2 class="InfoHeader">Group P3214</h2>
                    </td>
                    <td class="InfoCell">
                        <h2 class="InfoHeader">Variant 14210</h2>
                    </td>
                </tr>
                <tr class="MainRow">
                    <td class="PictureContainer" colspan="2">
                        <img src="images/picture.svg" height="100%" alt=""/>
                    </td>
                </tr>
                <tr class="MainRow">
                    <td class="FormContainer" colspan="2">
                        <form class="DataForm" action="" method="GET">
                            <fieldset class="FormFields">
                                <legend>
                                    <h3 class="LegendHeader">Variable values</h3>
                                </legend>
                                <table class="FormTable">
                                    <tr class="FormHeaderRow">
                                        <th class="TableHeader">
                                            Variable name
                                        </th>
                                        <th class="TableHeader">
                                            Value
                                        </th>
                                        <th class="TableHeader">
                                            Inputs
                                        </th>
                                    </tr>
                                    <tr class="FormRow">
                                        <td class="VariableContainer">
                                            <label class="VariableName">X</label>
                                        </td>
                                        <td class="ValueContainer">
                                            <label class="Value XVal" id="labelX">0</label>
                                        </td>
                                        <td class="ButtonContainer">
                                            <button type="button" class="SelectButton" onclick="setX(-5);">-5</button>
                                            <button type="button" class="SelectButton" onclick="setX(-4);">-4</button>
                                            <button type="button" class="SelectButton" onclick="setX(-3);">-3</button>
                                            <button type="button" class="SelectButton" onclick="setX(-2);">-2</button>
                                            <button type="button" class="SelectButton" onclick="setX(-1);">-1</button>
                                            <button type="button" class="SelectButton" onclick="setX(0);">0</button>
                                            <button type="button" class="SelectButton" onclick="setX(1);">1</button>
                                            <button type="button" class="SelectButton" onclick="setX(2);">2</button>
                                            <button type="button" class="SelectButton" onclick="setX(3);">3</button>
                                        </td>
                                    </tr>
                                    <tr class="FormRow">
                                        <td class="VariableContainer">
                                            <label class="VariableName">Y</label>
                                        </td>
                                        <td class="ValueContainer">
                                            <label class="Value YVal" id="labelY">0.0</label>
                                        </td>
                                        <td class="TextInputContainer">
                                            <input
                                                    class="TextInput"
                                                    type="text"
                                                    name="y"
                                                    placeholder="A number between -3 and 5"
                                                    min="-3"
                                                    max="5"
						    maxlength="8"
                                                    onchange="validateY()"
                                                    required/>
                                            <input type="hidden" name="x" id="x" value="0"/>
                                            <input type="hidden" name="r" id="r" value="1"/>
                                        </td>
                                    </tr>
                                    <tr class="FormRow">
                                        <td class="VariableContainer">
                                            <label class="VariableName">R</label>
                                        </td>
                                        <td class="ValueContainer">
                                            <label class="Value ZVal" id="labelR">1</label>
                                        </td>
                                        <td class="ButtonContainer">
                                            <button type="button" class="SelectButton" onclick="setR(1);">1</button>
                                            <button type="button" class="SelectButton" onclick="setR(2);">2</button>
                                            <button type="button" class="SelectButton" onclick="setR(3);">3</button>
                                            <button type="button" class="SelectButton" onclick="setR(4);">4</button>
                                            <button type="button" class="SelectButton" onclick="setR(5);">5</button>
                                        </td>
                                    </tr>
                                    <tr class="FormRow">
                                        <td class="SubmitButtonContainer" colspan="3">
                                            <input
                                                    class="SubmitButton"
                                                    type="submit"
                                                    name="Coordinates"
                                                    value="Сheck"/>
                                        </td>
                                    </tr>
                                </table>
                            </fieldset>
                        </form>
                    </td>
                </tr>

                <?php
                $records = get_all_records($filename);
                if (count($records) > 0) {
                    ?>
                    <tr>
                        <td colspan = "2">
                            <h3>History</h3>
                            <table class = "history">
                                <thead>
                                    <tr>
                                        <td header>Time</td>
                                        <td header>X</td>
                                        <td header>Y</td>
                                        <td header>R</td>
					                    <td header>Execution time (ms)</td>
                                        <td header>Result</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php

                                    for ($i = 0; $i < sizeof($records); $i++) {
                                        $record = get_fields($records[$i]);
                                        $checkResultAttribute = 'fail';
                                        $checkResultChar = "&cross;";
                                        if ($record[5] == 1) {
                                            $checkResultAttribute = 'success';
                                            $checkResultChar = "&checkmark;";
                                        }
                                        ?>
                                        <tr<?php if ($showAdded && ($i == 0)) {
                                            print(' justAdded');
                                        } ?>>
                                            <td><?php print($record[0]); ?></td>
                                            <td><?php print($record[1]); ?></td>
                                            <td><?php print($record[2]); ?></td>
                                            <td><?php print($record[3]); ?></td>
					                        <td><?php print($record[4]); ?></td>
                                            <td <?php print($checkResultAttribute); ?>><?php print($checkResultChar); ?></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </table>
        </div>
    </body>
</html>
