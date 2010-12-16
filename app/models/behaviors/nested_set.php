<?php
App::import('Behavior', 'Tree');
class NestedSetBehavior extends TreeBehavior {

  function roots(&$Model, $conditions = null, $fields = null, $order = null, $limit = null, $page = 1, $recursive = null) {
    $conditions = array($Model->escapeField($parent) . ' is null', $conditions);
    return $Model->find('all', compact('conditions', 'fields', 'order', 'limit', 'page', 'recursive'));
  }

  function tree(&$Model, $conditions = null, $fields = null, $order = null, $limit = null, $page = 1, $recursive = null, $id = null) {
	if ($id == null) {
		$nodes = $this->roots($Model, $conditions, $fields, $order, $limit, $page, $recursive);
	} else {
		$conditions = array($Model->escapeField($parent) => $id, $conditions);
		$nodes = $Model->find('all', compact('conditions', 'fields', 'order', 'limit', 'page', 'recursive'));
	}
	foreach ($nodes as $node) {
		$node[$Model.'s'] = tree(&$Model, $conditions, $fields, $order, $limit, $page, $recursive, $node[$Model][$Model->primaryKey]);
	}
  }
}