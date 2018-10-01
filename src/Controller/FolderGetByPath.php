<?php

namespace App\Controller;

use App\Repository\FolderRepository;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Folder;

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
        $this->folderRepository = $folderRepository;
    }

    /**
     * @Route(
     *     name="get_by_path",
     *     path="/folders/path/{path}",
     *     methods={"GET"},
     *     requirements={"path"=".*"},
     *     defaults={
     *         "_api_resource_class"=Folder::class,
     *         "_api_item_operation_name"="get_by_path"
     *     }
     *
     * )
     *
     * @param $path
     *
     */
    public function __invoke($path)
    {
        return $this->folderRepository->findByPath($path);
    }
}
