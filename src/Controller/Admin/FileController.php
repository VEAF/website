<?php

namespace App\Controller\Admin;

use App\Entity\File;
use App\Form\FileUploadType;
use App\Service\FileUploaderService;
use Kilik\TableBundle\Components\Column;
use Kilik\TableBundle\Components\Filter;
use Kilik\TableBundle\Components\Table;
use Kilik\TableBundle\Services\TableService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/file")
 */
class FileController extends AbstractController
{
    public function getTable(): Table
    {
        $queryBuilder = $this->getDoctrine()->getRepository(File::class)->createQueryBuilder('f')
            ->select('f, o')
            ->leftJoin('f.owner', 'o');

        $table = (new Table())
            ->setId('admin_file_list')
            ->setPath($this->generateUrl('admin_file_list_ajax'))
            ->setTemplate('admin/file/_list.html.twig')
            ->setQueryBuilder($queryBuilder, 'f');

        $table
            ->addColumn(
                (new Column())->setLabel('Uuid')
                    ->setSort(['f.uuid' => 'asc'])
                    ->setFilter((new Filter())
                        ->setField('f.uuid')
                        ->setName('f_uuid')
                    )
            );

        $table
            ->addColumn(
                (new Column())->setLabel('Type Mime')
                    ->setSort(['f.mimeType' => 'asc'])
                    ->setFilter((new Filter())
                        ->setField('f.mimeType')
                        ->setName('f_mimeType')
                    )
            );

        $table
            ->addColumn(
                (new Column())->setLabel('Taille')
                    ->setSort(['f.size' => 'asc'])
                    ->setFilter((new Filter())
                        ->setField('f.size')
                        ->setName('f_size')
                    )
            );

        $table
            ->addColumn(
                (new Column())->setLabel('Propriétaire')
                    ->setSort(['o.nickname' => 'asc'])
                    ->setFilter((new Filter())
                        ->setField('o.nickname')
                        ->setName('o_nickname')
                    )
            );

        $table
            ->addColumn(
                (new Column())->setLabel('Créé le')
                    ->setSort(['f.createdAt' => 'asc'])
                    ->setDisplayFormat(Column::FORMAT_DATE)
                    ->setDisplayFormatParams('d/m/Y')
                    ->setFilter((new Filter())
                        ->setField('f.createdAt')
                        ->setName('f_createdAt')
                        ->setDataFormat(Filter::FORMAT_DATE)
                    )
            );

        return $table;
    }

    /**
     * @Route("", name="admin_file_list")
     */
    public function list(TableService $tableService): Response
    {
        return $this->render('admin/file/list.html.twig', [
            'table' => $tableService->createFormView($this->getTable()),
        ]);
    }

    /**
     * @Route("/_ajax", name="admin_file_list_ajax")
     */
    public function _listAction(TableService $tableService, Request $request)
    {
        return $tableService->handleRequest($this->getTable(), $request);
    }

    /**
     * @Route("/upload", name="admin_file_upload")
     */
    public function upload(FileUploaderService $uploaderService, Request $request): Response
    {
        $form = $this->createForm(FileUploadType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $uploaderService->upload($form->get('file')->getData(), $this->getUser());

            $this->addFlash('success', 'Le fichier a été enregistré');

            return $this->redirectToRoute('admin_file_view', ['file' => $file->getUuid()]);
        }

        return $this->render('admin/file/upload.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{file}", name="admin_file_view")
     * @ParamConverter("file", options={"mapping": {"file": "uuid"}})
     */
    public function view(File $file): Response
    {
        return $this->render('admin/file/view.html.twig', [
            'file' => $file,
        ]);
    }

    /**
     * @Route("/{file}/delete", name="admin_file_delete")
     * @ParamConverter("file", options={"mapping": {"file": "uuid"}})
     */
    public function delete(Request $request, File $file): Response
    {
        throw new \Exception('WIP feature'); // @todo
    }
}
