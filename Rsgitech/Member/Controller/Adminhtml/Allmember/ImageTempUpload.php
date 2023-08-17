<?php
declare(strict_types=1);

namespace Rsgitech\Member\Controller\Adminhtml\Allmember;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\WriteInterface;
use Magento\MediaStorage\Model\File\Uploader;
use Magento\Store\Model\StoreManagerInterface;




class ImageTempUpload extends Action implements HttpPostActionInterface
{
    private WriteInterface $mediaDirectory;

    public function __construct(
		Context $context,
		Filesystem $filesystem,
        private Uploader $uploaderFactory,
        private StoreManagerInterface $storeManager,
	) {
		parent::__construct($context);	
        $this->mediaDirectory = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
	}


    public function execute(): ResultInterface
    {
        $jsonResult = $this->resultFactory->create(type:ResultFactory::TYPE_JSON);
    
        try{
            $fileUploader = $this->uploaderFactory->create(['fileId'=>'image']);
            $fileUploader->setAllowedExtensions(['jpg', 'jpeg', 'png']);
            $fileUploader->setAllowRenameFiles(flag:true);
            $fileUploader->setAllowCreateFolders(flag:true);
            $fileUploader->setFilesDispersion(flag:false);

            $imgPath = 'tmp/imageUploader/images';
            $result = $fileUploader->save($this->mediaDirectory->getAbsolutePath($imgPath));
            $mediaUrl = $this->storeManager->getStore()->getBaseUrl(type:UrlInterface::URL_TYPE_MEDIA);
            $fileName = ltrim(str_replace(search:'\\', replace:'/',$result['file']), characters:'/');
            $result['url'] = $mediaUrl . $imgPath . '/' . $fileName;

        } catch(LocalizedException $exception) {
            return $jsonResult->setData(['errorcode'=>0, 'error' => $exception->getMessage()]);       
        } catch(\Exception $e) {
            return $jsonResult->setData(
                ['errorcode' => 0, 'error' => __('An error occured, please try again later.')]
            );
        }
    }
    
}









?>