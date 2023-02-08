<?php

namespace App\Controller\Admin;

use App\Entity\ExternalImage;
use Kilik\TableBundle\Components\Column;
use Kilik\TableBundle\Components\Filter;
use Kilik\TableBundle\Components\FilterSelect;
use Kilik\TableBundle\Components\Table;
use Kilik\TableBundle\Services\TableService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/external-image")
 */
class ExternalImageController extends AbstractController
{
    public function getTable(): Table
    {
        $queryBuilder = $this->getDoctrine()->getRepository(ExternalImage::class)->createQueryBuilder('e')
            ->select('e');

        $table = (new Table())
            ->setId('external_image_list')
            ->setPath($this->generateUrl('admin_external_image_list_ajax'))
            ->setTemplate('admin/external-image/_list.html.twig')
            ->setQueryBuilder($queryBuilder, 'e');

        $table
            ->addColumn(
                (new Column())->setLabel('Source')
                    ->setSort(['e.sourceType' => 'asc'])
                    ->setFilter((new FilterSelect())
                        ->setField('e.sourceType')
                        ->setName('e_sourceType')
                        ->setChoices(array_flip(ExternalImage::SOURCE_TYPES))
                        ->setPlaceholder('-')
                        ->disableTranslation() // disable translations of placeholder and values
                    )
                    ->setDisplayCallback(function ($value, $row, $lines) {
                        /** @var ExternalImage $externalImage */
                        $externalImage = $row['object'];

                        return $externalImage->getSourceTypeAsString();
                    })

            );

        $table
            ->addColumn(
                (new Column())->setLabel('Chemin')
                    ->setSort(['e.sourcePath' => 'asc'])
                    ->setFilter((new Filter())
                        ->setField('e.sourcePath')
                        ->setName('e_sourcePath')
                    )
            );

        $table
            ->addColumn(
                (new Column())->setLabel('Auteur')
                    ->setSort(['e.author' => 'asc'])
                    ->setFilter((new Filter())
                        ->setField('e.author')
                        ->setName('e_author')
                    )
            );

        $table
            ->addColumn(
                (new Column())->setLabel('Créée le')
                    ->setSort(['e.createdAt' => 'asc'])
                    ->setDisplayFormat(Column::FORMAT_DATE)
                    ->setDisplayFormatParams('d/m/Y')
                    ->setFilter((new Filter())
                        ->setField('e.createdAt')
                        ->setName('e_createdAt')
                        ->setDataFormat(Filter::FORMAT_DATE)
                    )
            );

        $table
            ->addColumn(
                (new Column())->setLabel('Url')
                    ->setSort(['e.url' => 'asc'])
                    ->setFilter((new Filter())
                        ->setField('e.url')
                        ->setName('e_url')
                    )
            );

        return $table;
    }

    /**
     * @Route("", name="admin_external_image_list")
     */
    public function list(TableService $tableService): Response
    {
        return $this->render('admin/external-image/list.html.twig', [
            'table' => $tableService->createFormView($this->getTable()),
        ]);
    }

    /**
     * @Route("/_ajax", name="admin_external_image_list_ajax")
     */
    public function _listAction(TableService $tableService, Request $request)
    {
        return $tableService->handleRequest($this->getTable(), $request);
    }
}
