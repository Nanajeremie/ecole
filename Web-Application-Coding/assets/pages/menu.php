<?php
function menu_item(string $titre, string $lien, string $icone)
{
    $classe = "nav-link text-left";
    if (stristr($_SERVER['SCRIPT_NAME'], $lien)) {
        $classe .= " active badge badge-secondary";
    }
    $page=explode('.',$lien);
    return <<<HTML
        <li class="nav-item">
            <a class="$classe" href="$lien?page=$page[0]">
                <i class="$icone d-inline px-2"></i>
                <span class="d-inline">$titre</span>
            </a>
        </li>
HTML;
}

function menu_item_collapse(string $titre, string $lien, string $icone, array $sous_lien, array $titre_sous_lien)
{

    $classe = "collapse";
    $item1 = ' ';
    foreach ($sous_lien as $item) {
        if (stristr($_SERVER['SCRIPT_NAME'], $item)) {
            $classe .= " show";
            $item1 = $item;
        }
    }

    $sous_lien_text = "";

    for ($j = 0; $j < count($sous_lien); $j++) {
        $sous_lien_text .= "<a class='collapse-item";
        if ($sous_lien[$j] == $item1)
            $sous_lien_text .= " active custom-bg-light text-dark'";
        else
            $sous_lien_text .= " ' ";
            $page=explode('.',$sous_lien[$j]);
            $sous_lien_text .= "href='$sous_lien[$j]?page=$page[0]'><i class='fas fa-angle-right pr-2'></i>$titre_sous_lien[$j]</a>";
    }

    $lien = "<li class='nav-item'>
                    <a class='nav-link collapsed w-auto text-left' href='#' data-toggle='collapse' data-target='#$titre' aria-expanded='true' aria-controls='$titre'>
                        <i class='$icone d-inline px-2'></i>
                        <span class='d-inline'>$titre</span>
                    </a>
                    <div id='$titre' class='$classe' aria-labelledby='headingUtilities' data-parent='#accordionSidebar'>
                        <div class='bg-white mx-2 py-2 collapse-inner rounded'>
                            $sous_lien_text
                        </div>
                    </div>
                </li>";

    echo $lien;
}

?>