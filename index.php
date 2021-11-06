<?php

spl_autoload_register(function ($class_name) {
    include $class_name . '.php';
});

/**
 * @var \classes\DataProcessor $processor
 */
$processor = new \classes\DataProcessor('data/chemicals.json');

?>
<!DOCTYPE html>
<html>
<head>
    <style type="text/css">
        li {
            list-style: none;
            float: left;
            min-width: 400px;
        }
        li:hover{
            background-color: red;
            cursor: pointer;
            color: white;
        }


        .missing {
            color: blue;
        }

        .medicine {
            background-color: pink;
        }

        .poison {
            background-color: lightgreen;
        }

        .disease {
            background-color: lightgray;
        }

        .acid {
            background-color: greenyellow;
        }

        .pyrotechnic {
            background-color: lightblue;
        }

        .secret {
            font-style: italic;
        }
        div{
            border: thin solid black;
            border-radius: 25px;
            display: inline-block;
            padding: 5px;
            cursor: pointer;
        }
        div:hover{
            background-color: lightgray;
        }
    </style>
    <script type="text/javascript">
        function toggleVisible() {
            [].forEach.call(document.querySelectorAll('.missing'), function (el) {
                if (el.style.display === "none") {
                    el.style.display = "block";
                } else {
                    el.style.display = "none";
                }
            });
        }
    </script>
</head>
<body>
<div onclick="toggleVisible()">
    <a>Toggle rarer Base</a>
</div>
<ul>
    <?php
    foreach ($processor->getChemicals() as $chemical) {
        $missingBase = is_array($chemical->getMissingParentsBase());
        if (in_array('base', $chemical->getTags())) {
            continue;
        }


        echo '<li class="'
            .($missingBase ? 'missing ' : '')
            . implode(" ", $chemical->getTags()) . '" 
             '.($missingBase?'style="display:none;" data-missing="'.implode('|', $chemical->getMissingParentsBase()).'"':'').'>' . ($chemical->getName() ==''?'['.$chemical->getId().']':$chemical->getName()) .
            '</li>';
    }
    ?>
</ul>
</body>
</html>

