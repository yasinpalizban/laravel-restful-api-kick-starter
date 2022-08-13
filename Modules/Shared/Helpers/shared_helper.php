<?php
function serializeMessages($data): string
{

    $line = " ";
    foreach ($data as $item) {
        $line .= $item;
        $line .= "\n ";

    }

    return $line;
}

function compareDates($first, $second): bool
{


    if (isset($first) and count($first[0]) > 0) {
        $first = strtotime($first[0]['start_date']);
        $second = strtotime($second[0]['end_date']);
        if ($first < $second) {
            return true;
        } else {
            return false;
        }
    }
    return false;
}

function stopIt($test)
{

    header("HTTP/1.1  409 miniResponse");
    $json = json_encode([
        'null' => is_null($test),
        'obj' => is_object($test),
        'array' => is_array($test),
        'empty' => empty($test),
        'test' => $test,
    ]);
    echo $json;
    exit();
}


function showIt($test = 'nothing')
{

    header("HTTP/1.1  409 miniResponse");
    $json = json_encode($test);
    echo $json;
    exit();
}

function showError($test = 'nothing')
{

    header("HTTP/1.1  409 miniResponse");
    $json = json_encode($test);
    print_r(['error' => $json]);
    exit();
}


function paginationFields($pager, $page): array
{

    if (!is_null($pager['next_page_url'])) {
        $exp = explode('?', $pager['next_page_url']);
        $next = [];
        parse_str($exp[1], $next);
        $next = $next['page'];
    } else {
        $next = null;
    }

    return [
        'uri' => $pager['path'],
        'hasMore' => !($next == null),
        'total' => $pager['total'],
        'perPage' => $pager['per_page'],
        'pageCount' => $page,
        'pageSelector' => null,
        'currentPage' => $pager['current_page'],
        'next' => $next,
        'previous' => (is_null($next) ? $page - 1 : null),
        'segment' => 0
    ];
}
