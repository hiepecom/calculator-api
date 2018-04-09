<?php

namespace Rezolve\Calculator\Controller;

use Magento\Framework\Webapi\Rest\Response\FieldsFilter;
use Magento\Framework\Config\ConfigOptionsListConstants;

class Rest extends \Magento\Webapi\Controller\Rest
{
    /**
     * @var DeploymentConfig
     */
    private $deploymentConfig;

    /**
     * @var Rest\InputParamsResolver
     */
    private $inputParamsResolver;

    /**
     * Get deployment config
     *
     * @return DeploymentConfig
     */
    private function getDeploymentConfig()
    {
        if (!$this->deploymentConfig instanceof \Magento\Framework\App\DeploymentConfig) {
            $this->deploymentConfig = \Magento\Framework\App\ObjectManager::getInstance()
                ->get('Magento\Framework\App\DeploymentConfig');
        }
        return $this->deploymentConfig;
    }

    /**
     * Execute API request
     *
     * @return void
     * @throws AuthorizationException
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Webapi\Exception
     */
    protected function processApiRequest()
    {
        $inputParams = $this->getInputParamsResolver()->resolve();

        $route = $this->getInputParamsResolver()->getRoute();
        $serviceMethodName = $route->getServiceMethod();
        $serviceClassName = $route->getServiceClass();

        $service = $this->_objectManager->get($serviceClassName);
        /** @var \Magento\Framework\Api\AbstractExtensibleObject $outputData */
        $outputData = call_user_func_array([$service, $serviceMethodName], $inputParams);
        $outputData = $this->serviceOutputProcessor->process(
            $outputData,
            $serviceClassName,
            $serviceMethodName
        );
        if ($this->_request->getParam(FieldsFilter::FILTER_PARAMETER) && is_array($outputData)) {
            $outputData = $this->fieldsFilter->filter($outputData);
        }
        $header = $this->getDeploymentConfig()->get(ConfigOptionsListConstants::CONFIG_PATH_X_FRAME_OPT);
        if ($header) {
            $this->_response->setHeader('X-Frame-Options', $header);
        }
        $this->_response->prepareResponse($outputData);
        if ($serviceClassName == 'Rezolve\Calculator\Api\CalculatorInterface') {
            $this->_response->setHttpResponseCode(200);
            $this->_response->setHeader('Content-type', 'application/json', true);
            $this->_response->setBody($outputData);
        }
    }

    /**
     * The getter function to get InputParamsResolver object
     *
     * @return \Magento\Webapi\Controller\Rest\InputParamsResolver
     *
     * @deprecated
     */
    private function getInputParamsResolver()
    {
        if ($this->inputParamsResolver === null) {
            $this->inputParamsResolver = \Magento\Framework\App\ObjectManager::getInstance()
                ->get(\Magento\Webapi\Controller\Rest\InputParamsResolver::class);
        }
        return $this->inputParamsResolver;
    }
}
