<?php

namespace App\Controller;

use App\Repository\FolderRepository;


/**
 * Class FolderGetByPath
 *
 * @package App\Controller
 */
class FolderGetByPath
{
    /**
     * @var FolderRepository
     */
    private $folderRepository;

    public function __construct(FolderRepository $folderRepository)
    {
        die('112');
        $this->folderRepository = $folderRepository;
    }

    public function __invoke($path)
    {
        die('11');


        return $this->folderRepository->findByPath($path);
    }
}