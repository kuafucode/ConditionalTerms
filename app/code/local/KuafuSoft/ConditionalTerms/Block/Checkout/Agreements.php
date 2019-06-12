<?php
class KuafuSoft_ConditionalTerms_Block_Checkout_Agreements extends Mage_Checkout_Block_Agreements
{
    public function getAgreements()
    {
        if (!$this->hasAgreements()) {
            if (!Mage::getStoreConfigFlag('checkout/options/enable_agreements')) {
                $agreements = array();
            } else {
                $agreements = Mage::getModel('checkout/agreement')->getCollection()
                    ->addStoreFilter(Mage::app()->getStore()->getId())
                    ->addFieldToFilter('is_active', 1);
                $address = Mage::getSingleton('checkout/session')->getQuote()->getShippingAddress();
                foreach($agreements as $agreement) {
                	if(!$agreement->getConditions()->validate($address)) {
                		$agreements->removeItemByKey($agreement->getId());
                	}
                }
            }
            $this->setAgreements($agreements);
        }
        return $this->getData('agreements');
    }
}
