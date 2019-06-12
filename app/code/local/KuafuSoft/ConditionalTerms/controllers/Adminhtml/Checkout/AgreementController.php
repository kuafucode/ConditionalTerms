<?php
require_once 'Mage/Adminhtml/controllers/Checkout/AgreementController.php';
class KuafuSoft_ConditionalTerms_Adminhtml_Checkout_AgreementController extends Mage_Adminhtml_Checkout_AgreementController
{
    public function editAction()
    {
        $this->_title($this->__('Sales'))->_title($this->__('Terms and Conditions'));

        $id  = $this->getRequest()->getParam('id');
        $agreementModel  = Mage::getModel('checkout/agreement');

        if ($id) {
            $agreementModel->load($id);
            if (!$agreementModel->getId()) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('checkout')->__('This condition no longer exists.')
                );
                $this->_redirect('*/*/');
                return;
            }
        }

        $this->_title($agreementModel->getId() ? $agreementModel->getName() : $this->__('New Condition'));

        $data = Mage::getSingleton('adminhtml/session')->getAgreementData(true);
        if (!empty($data)) {
            $agreementModel->setData($data);
        }

        $agreementModel->getConditions()->setJsFormObject('rule_conditions_fieldset');
        Mage::register('checkout_agreement', $agreementModel);

        $this->_initAction()
            ->_addBreadcrumb($id ? Mage::helper('checkout')->__('Edit Condition') :  Mage::helper('checkout')->__('New Condition'), $id ?  Mage::helper('checkout')->__('Edit Condition') :  Mage::helper('checkout')->__('New Condition'))
            ->_addContent($this->getLayout()->createBlock('adminhtml/checkout_agreement_edit')->setData('action', $this->getUrl('*/*/save')))
            ->renderLayout();
    }

    public function saveAction()
    {
        if ($postData = $this->getRequest()->getPost()) {
            $model = Mage::getSingleton('checkout/agreement');

            $postData['conditions'] = $postData['rule']['conditions'];
            unset($postData['rule']);
            
            $model->loadPost($postData);
            
            $model->setData($postData);

            try {
                $model->save();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('checkout')->__('The condition has been saved.'));
                $this->_redirect('*/*/');

                return;
            }
            catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('checkout')->__('An error occurred while saving this condition.'));
            }

            Mage::getSingleton('adminhtml/session')->setAgreementData($postData);
            $this->_redirectReferer();
        }
    }
}
