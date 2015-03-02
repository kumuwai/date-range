<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Demo of Date</title>
    <style type="text/css">
        table {
            margin: 20px;
            border-collapse: collapse;
        }
        td {
            padding: 6px;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>
    <?php
        require_once '../vendor/autoload.php';
        use Kumuwai\DateRange\DateRange;

        $start = (isset($_REQUEST['start']) ? $_REQUEST['start'] : '');
        $end = (isset($_REQUEST['end']) ? $_REQUEST['end'] : '');

        try {
            $date = new DateRange($start, $end);
        } catch (Exception $e) {
            $date = new DateRange;
        }
    ?>
    <form>
        <label for="start">Start:</label>
        <input type="text" id="start" name="start" value="<?php echo $start; ?>">
        <label for="end">End:</label>
        <input type="text" id="end" name="end" value="<?php echo $end; ?>">
        <button type="submit">Submit</button>
    </form>

    <h2>FormattedCarbon Default Styles</h2>
    <table>
        <thead><tr><th>Style Name</th><th>PHP Format</th><th>Example Result</th></tr></thead>
    <?php
        foreach($date->getStyles() as $style=>$value) {
            $value = (is_string($value)) ? $value : '(function)';
            echo "<tr><td>$style</td><td>$value</td><td>{$date->style($style)}</td></tr>";
        }
    ?></table>
    
    <h2>Carbon Defined Styles</h2>
    <table>
        <thead><tr><th>Style Name</th><th>Example Result</th></tr></thead>
    <?php
        foreach(['Date','FormattedDate','Time','DateTime','DayDateTime','Atom','Cookie',
            'Iso8601','Rfc822','Rfc850','Rfc1036','Rfc1123','Rfc2822','Rfc3339','Rss','W3c'] as $style) {
            echo "<tr><td>$style</td><td>{$date->style($style)}</td></tr>";
        }
    ?>
    </table>
</body>
</html>

