<?php
/**
 * Copyright © 2020 Wyomind. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Wyomind\SimpleGoogleShopping\Ui\Component\Listing\Column;

class Actions extends \Magento\Ui\Component\Listing\Columns\Column
{
    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var \Magento\Framework\Authorization
     */
    private $authorization = null;

    /**
     * @var \Wyomind\Framework\Helper\License
     */
    protected $licenseHelper;

    /**
     * Actions constructor.
     * @param \Magento\Framework\View\Element\UiComponent\ContextInterface $context
     * @param \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory
     * @param \Magento\Framework\UrlInterface $urlBuilder
     * @param \Magento\Framework\Authorization $authorization
     * @param \Wyomind\Framework\Helper\License $licenseHelper
     * @param array $components
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\UiComponent\ContextInterface $context,
        \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory,
        \Magento\Framework\UrlInterface $urlBuilder,
        \Magento\Framework\Authorization $authorization,
        \Wyomind\Framework\Helper\License $licenseHelper,
        array $components = [],
        array $data = []
    )
    {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->authorization = $authorization;
        $this->licenseHelper = $licenseHelper;
    }

    /**
     * Prepare Data Source
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $name = $this->getData('name');

                if (isset($item['simplegoogleshopping_id'])) {
                    if ($this->authorization->isAllowed('Wyomind_SimpleGoogleShopping::edit')) {
                        $item[$name]['edit'] = [
                            'href' => $this->urlBuilder->getUrl(
                                'simplegoogleshopping/feeds/edit',
                                ['id' => $item['simplegoogleshopping_id']]
                            ),
                            'label' => __('Edit')
                        ];
                    }

                    if ($this->authorization->isAllowed('Wyomind_SimpleGoogleShopping::generate')) {
                        $item[$name]['generate'] = [
                            'href' => $this->urlBuilder->getUrl(
                                'simplegoogleshopping/feeds/generate',
                                ['id' => $item['simplegoogleshopping_id']]
                            ),
                            'label' => __('Generate'),
                            'confirm' => [
                                'title' => __('Generate data feed'),
                                'message' => __('Generate a data feed can take a while. Are you sure you want to generate it now?')
                            ]
                        ];
                    }

                    $item[$name]['preview'] = [
                        'href' => $this->urlBuilder->getUrl(
                            'simplegoogleshopping/feeds/preview',
                            ['simplegoogleshopping_id' => $item['simplegoogleshopping_id']]
                        ),
                        'label' =>  __('Preview (%1 items)', $this->licenseHelper->getStoreConfig('simplegoogleshopping/system/preview')),
                        'target' => '_blank'
                    ];

                    if ($this->authorization->isAllowed('Wyomind_SimpleGoogleShopping::delete')) {
                        $item[$name]['delete'] = [
                            'href' => $this->urlBuilder->getUrl(
                                'simplegoogleshopping/feeds/delete',
                                ['id' => $item['simplegoogleshopping_id']]
                            ),
                            'label' => __('Delete'),
                            'confirm' => [
                                'title' => __('Delete a feed'),
                                'message' => __('Are you sure you want to delete the feed <b>%1</b>', $item['simplegoogleshopping_filename'])
                            ]
                        ];
                    }
                }
            }
        }

        return $dataSource;
    }
}