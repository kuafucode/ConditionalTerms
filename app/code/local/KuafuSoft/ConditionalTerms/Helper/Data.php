<?php 
class KuafuSoft_ConditionalTerms_Helper_Data extends Mage_Checkout_Helper_Data
{
    public function getRequiredAgreementIds() {
        if (is_null($this->_agreements)) {
            if (!Mage::getStoreConfigFlag('checkout/options/enable_agreements')) {
                $this->_agreements = array();
            } else {
                $agreements = Mage::getModel('checkout/agreement')->getCollection()
                    ->addStoreFilter(Mage::app()->getStore()->getId())
                    ->addFieldToFilter('is_active', 1);
                $address = Mage::getSingleton('checkout/session')->getQuote()->getShippingAddress();
                $agreement_ids = array();
                foreach($agreements as $key => $agreement) {
                    if($agreement->getConditions()->validate($address)) {
                        $agreement_ids[] = $agreement->getId();
                    }
                }
                $this->_agreements = $agreement_ids;
            }
        }
        return $this->_agreements;
    }
}
