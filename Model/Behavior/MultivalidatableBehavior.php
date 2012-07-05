<?php 
class MultivalidatableBehavior extends ModelBehavior { 

    /** 
     * Stores previous validation ruleset 
     * 
     * @var Array 
     */ 
    var $__oldRules = array(); 

    /** 
     * Stores Model default validation ruleset 
     * 
     * @var unknown_type 
     */ 
    var $__defaultRules = array(); 

    function setup(&$model, $config = array()) { 
        $this->__defaultRules[$model->name] = $model->validate; 
		$model->validationSets['default'] = $model->validate;
    } 

    /** 
     * Installs a new validation ruleset 
     * 
     * @param Object $model 
     * @param Mixed $rules 
     */ 
    function setValidation(&$model) {
		$rules = func_get_args();
		array_shift($rules);
		$rulesCount = count($rules);
		if($rulesCount >= 1) {
			$this->_setValidationSets($model, $rules);
		}
    } 

    /** 
     * Restores previous validation ruleset 
     * 
     * @param Object $model 
     */ 
    function restoreValidation(&$model) { 
        $model->validate = $this->__oldRules[$model->name]; 
    } 

    /** 
     * Restores default validation ruleset 
     * 
     * @param Object $model 
     */ 
    function restoreDefaultValidation(&$model) { 
        $model->validate = $this->__defaultRules[$model->name]; 
    } 

	public function _setValidationSets(&$model, $rules=array()) {
		$this->_setValidation($model,$rules);
		foreach($rules as $rule) {
			$this->_mergeValidation($model, $rule);
		}
	}

    /** 
     * Merge validation ruleset with existing
     * 
     * @param Object $model 
     * @param Array $rules 
     */
	
	public function _mergeValidation(&$model, $_rule) {
		$rule = $model->validationSets[$_rule];
		$model->validate = array_merge($model->validate, $rule);
	}
	
    /** 
     * Sets a new validation ruleset, saving the previous 
     * 
     * @param Object $model 
     * @param Array $rules 
     */ 
    function _setValidation(&$model, $rules) {
		$rule = array_shift($rules);
		$this->__oldRules[$model->name] = $model->validate;
		$model->validate = $model->validationSets[$rule];
    } 

} 
