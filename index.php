<?php
//init
$appName = 'fm';
BingoExt_Init::init($appName);
Bingo_Timer::start($appName);

try {
    //router
    $objFrontController = Bingo_Controller_Front::getInstance(array(
        'actionDir' => MODULE_ACTION_PATH,
        'beginRouterIndex' => 1,
        'defaultHttpRouter' => $appName,
        'usePathinfo' => true,
        'notFoundRouter' => 'error',
    ));

    //$objFrontController->addStaticRouter('bb/aa', 'aa');
    traceLog();
    //dispatch
    $objFrontController->dispatch();
} catch (Exception $e) {
    Bingo_log::warning($e->getMessage());
    header("HTTP/1.1 404 Not Found");
}

Bingo_Timer::end($appName);

function traceLog()
{
    Bingo_log::pushNotice('method', $_SERVER['REQUEST_METHOD']);
    Bingo_Log::pushNotice('uri', $_SERVER['REQUEST_URI']);
    Bingo_log::pushNotice('user_agent', $_SERVER['HTTP_USER_AGENT']);
    Bingo_Log::pushNotice('user_ip', Bd_Ip::getClientIP());
}
