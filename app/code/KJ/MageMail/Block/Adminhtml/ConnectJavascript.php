<?php
namespace KJ\MageMail\Block\Adminhtml;
use Magento\Framework\App\DeploymentConfig;
use Magento\Framework\Encryption\Encryptor;

/**
 * @method KJ_MageMail_Block_Adminhtml_ConnectJavascript setElement($element)
 */
class ConnectJavascript extends \Magento\Config\Block\System\Config\Form\Field
{
    /**
     * @var \KJ\MageMail\Helper\Data
     */
    protected $mageMailHelper;
    private $_deploymentConfig;

    public function __construct(\KJ\MageMail\Helper\Data $mageMailHelper,
        DeploymentConfig $deploymentConfig,
        \Magento\Backend\Block\Template\Context $context, array $data = [])
    {
        $this->_deploymentConfig = $deploymentConfig;
        $this->mageMailHelper = $mageMailHelper;
        parent::__construct($context, $data);
    }

    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $this->setTemplate('KJ_MageMail::system/config/connect_js.phtml');
        return $this->_toHtml();
    }

    public function getGenerateApiKeyUrl()
    {
        return $this->getUrl('*/manage/generateApiKey');
    }

    public function getConnectUrl()
    {
        $url = '//' . $this->mageMailHelper->getMageMailDomainWithoutTrailingSlash() . '/connector/connect';
        return $url;
    }

    public function getCreateAccountUrl()
    {
        $url = '//' . $this->mageMailHelper->getMageMailDomainWithoutTrailingSlash() . '/connector/create';
        return $url;
    }

    public function generateUsername()
    {
        $domain = $this->mageMailHelper->getMagentoBaseUrl();
        $domain = str_replace(".", "_", $domain);
        $username = preg_replace("/[^A-Za-z0-9_]/", '', $domain);

        return $username;
    }

    public function generatePassword()
    {
        $crypKey = (string)$this->_deploymentConfig->get(Encryptor::PARAM_CRYPT_KEY);
        return substr(md5(time() . $crypKey), 0, 10);
    }

    public function getGeneralEmail()
    {
        return $this->mageMailHelper->getGeneralEmail();
    }

    public function getSaveConnectedStatusUrl()
    {
        return $this->getUrl('*/manage/saveConnectedStatus');
    }

    public function getButtonHtml()
    {
        return '';
    }

    public function getMagentoBaseUrl()
    {
        return $this->mageMailHelper->getMagentoBaseUrl();
    }

    public function getSecureApiUrl()
    {
        return $this->mageMailHelper->getSecureApiUrl();
    }

    public function isConnected()
    {
        return $this->mageMailHelper->isConnected();
    }

    public function getTablePrefix()
    {
        return $this->_deploymentConfig->get('db/table_prefix');
    }

    public function getDisconnectUrl()
    {
        return $this->getUrl('*/manage/disconnect');
    }

    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $html = $this->_renderValue($element);
        $html .= $this->_renderHint($element);

        return $this->_decorateRowHtml($element, $html);
    }
}