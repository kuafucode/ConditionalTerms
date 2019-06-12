<?php
class KuafuSoft_ConditionalTerms_Block_Adminhtml_Checkout_Agreement_Edit_Form extends Mage_Adminhtml_Block_Checkout_Agreement_Edit_Form
{
    /**
     *
     * return Mage_Adminhtml_Block_Widget_Form
     */
    protected function _prepareForm()
    {
    	parent::_prepareForm();
    	$form = $this->getForm();

    	$form->setHtmlIdPrefix('rule_');
    	$renderer = Mage::getBlockSingleton('adminhtml/widget_form_renderer_fieldset')
    	->setTemplate('promo/fieldset.phtml')
    	->setNewChildUrl($this->getUrl('*/promo_catalog/newConditionHtml/form/rule_conditions_fieldset'));
    	
    	$fieldset = $form->addFieldset('conditions_fieldset', array(
    			'legend'=>Mage::helper('catalogrule')->__('Conditions (leave blank for all products)'))
    		)->setRenderer($renderer);

    	$model  = Mage::registry('checkout_agreement');
    	//$model = Mage::getModel('salesrule/rule');
    	$fieldset->addField('conditions', 'text', array(
    			'name' => 'conditions',
    			'label' => Mage::helper('catalogrule')->__('Conditions'),
    			'title' => Mage::helper('catalogrule')->__('Conditions'),
    			'required' => true,
    		))->setRule($model)->setRenderer(Mage::getBlockSingleton('rule/conditions'));
    	
        $form->setValues($model->getData());
        return $this;
    }
}
