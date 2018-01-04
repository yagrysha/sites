<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {include_component} plugin
 *
 * Type:     function<br>
 * Name:     include_component<br>
 * Purpose:  include_component
 * @link 
 * @author  Yaroslav Gryshanovich
 * @param array
 * @param Smarty
 * @return string|null 
 *                     
 */
function smarty_function_include_component($params, &$smarty)
{
    if (empty($params['name'])) {
        $smarty->_trigger_fatal_error("[plugin] parameter 'name' cannot be empty");
        return;
    }
    list($mn, $cn)= explode('/',$params['name']);
    Controller::include_component($mn, $cn, $params, @$params['cacheKey'], @$params['cacheTime']);
}

?>
