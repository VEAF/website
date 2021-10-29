<?php

namespace App\Controller\Admin;

use App\Entity\Module;
use App\Form\ModuleType;
use App\Manager\ModuleManager;
use App\Service\FileUploaderService;
use Kilik\TableBundle\Components\Column;
use Kilik\TableBundle\Components\Filter;
use Kilik\TableBundle\Components\FilterSelect;
use Kilik\TableBundle\Components\Table;
use Kilik\TableBundle\Services\TableService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/module")
 */
class ModuleController extends AbstractController
{
    public function getTable(): Table
    {
        $queryBuilder = $this->getDoctrine()->getRepository(Module::class)->createQueryBuilder('m')
            ->select('m, GROUP_CONCAT(DISTINCT r.name) AS roles, GROUP_CONCAT(DISTINCT s.name) AS systems')
            ->leftJoin('m.roles', 'r')
            ->leftJoin('m.systems', 's')
            ->groupBy('m');

        $booleanFilterChoices = ['Oui' => true, 'Non' => false];

        $table = (new Table())
            ->setId('admin_module_list')
            ->setPath($this->generateUrl('admin_module_list_ajax'))
            ->setTemplate('admin/module/_list.html.twig')
            ->setQueryBuilder($queryBuilder, 'm');

        $table
            ->addColumn(
                (new Column())->setLabel('Type')
                    ->setSort(['m.type' => 'asc'])
                    ->setFilter((new FilterSelect())
                        ->setField('m.type')
                        ->setName('m_type')
                        ->setChoices(array_flip(Module::TYPES))
                        ->setPlaceholder('-')
                        ->disableTranslation() // disable translations of placeholder and values
                    )
                    ->setDisplayCallback(function ($value, $row, $lines) {
                        /** @var Module $module */
                        $module = $row['object'];

                        return $module->getTypeAsString();
                    })
            );

        $table
            ->addColumn(
                (new Column())->setLabel('Code')
                    ->setSort(['m.code' => 'asc'])
                    ->setFilter((new Filter())
                        ->setField('m.code')
                        ->setName('m_code')
                    )
            );

        $table
            ->addColumn(
                (new Column())->setLabel('Nom')
                    ->setSort(['m.name' => 'asc'])
                    ->setFilter((new Filter())
                        ->setField('m.name')
                        ->setName('m_name')
                    )
            );

        $table
            ->addColumn(
                (new Column())->setLabel('Rôles')
                    ->setSort(['roles' => 'asc'])
                    ->setFilter((new Filter())
                        ->setField('GROUP_CONCAT(DISTINCT r.name)')
                        ->setHaving(true)
                        ->setName('roles')
                    )
            );

        $table
            ->addColumn(
                (new Column())->setLabel('Systèmes')
                    ->setSort(['systems' => 'asc'])
                    ->setFilter((new Filter())
                        ->setField('GROUP_CONCAT(DISTINCT s.name)')
                        ->setHaving(true)
                        ->setName('systems')
                    )
            );

        return $table;
    }

    /**
     * @Route("", name="admin_module_list")
     */
    public function list(TableService $tableService): Response
    {
        return $this->render('admin/module/list.html.twig', [
            'table' => $tableService->createFormView($this->getTable()),
        ]);
    }

    /**
     * @Route("/_ajax", name="admin_module_list_ajax")
     */
    public function _listAction(TableService $tableService, Request $request)
    {
        return $tableService->handleRequest($this->getTable(), $request);
    }

    /**
     * @Route("/add", name="admin_module_add")
     * @Route("/{module}/edit", name="admin_module_edit")
     */
    public function edit(FileUploaderService $uploaderService, ModuleManager $moduleManager, Request $request, Module $module = null): Response
    {
        if (null === $module) {
            $module = new Module();
        }
        $form = $this->createForm(ModuleType::class, $module);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $uploadedImageHeader */
            $uploadedImageHeader = $form->get('imageHeader')->getData();
            if ($uploadedImageHeader) {
                $imageHeader = $uploaderService->upload($uploadedImageHeader, $this->getUser());
                $module->setImageHeader($imageHeader);
            }

            /** @var UploadedFile $uploadedImage */
            $uploadedImage = $form->get('image')->getData();
            if ($uploadedImage) {
                $image = $uploaderService->upload($uploadedImage, $this->getUser());
                $module->setImage($image);
            }

            $moduleManager->save($module, true);
            $this->addFlash('success', 'Le module a été enregistré');

            return $this->redirectToRoute('admin_module_view', ['module' => $module->getId()]);
        }

        return $this->render('admin/module/edit.html.twig', [
            'form' => $form->createView(),
            'module' => $module,
        ]);
    }

    /**
     * @Route("/{module}", name="admin_module_view")
     */
    public function view(Module $module): Response
    {
        return $this->render('admin/module/view.html.twig', [
            'module' => $module,
        ]);
    }
}
