<?php

namespace App\Controller\Admin\Calendar;

use App\Entity\Calendar\Event;
use App\Entity\User;
use App\Form\CalendarEventAdminType;
use App\Manager\Calendar\EventManager;
use App\Service\FileUploaderService;
use Kilik\TableBundle\Components\Column;
use Kilik\TableBundle\Components\Filter;
use Kilik\TableBundle\Components\FilterSelect;
use Kilik\TableBundle\Components\Table;
use Kilik\TableBundle\Services\TableService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/calendar/event")
 */
class EventController extends AbstractController
{
    public function getTable(): Table
    {
        $queryBuilder = $this->getDoctrine()->getRepository(Event::class)->createQueryBuilder('e')
            ->select('e, o, map')
            ->leftJoin('e.owner', 'o')
            ->leftJoin('e.map', 'map');

        $booleanFilterChoices = ['Oui' => true, 'Non' => false];

        $table = (new Table())
            ->setId('admin_calendar_event_list')
            ->setPath($this->generateUrl('admin_calendar_event_list_ajax'))
            ->setTemplate('admin/calendar/event/_list.html.twig')
            ->setQueryBuilder($queryBuilder, 'e');

        $table
            ->addColumn(
                (new Column())->setLabel('Début')
                    ->setSort(['e.startDate' => 'asc'])
                    ->setDisplayFormat(Column::FORMAT_DATE)
                    ->setDisplayFormatParams('d/m/Y H:i')
                    ->setFilter((new Filter())
                        ->setField('e.startDate')
                        ->setName('e_startDate')
                        ->setDataFormat(Filter::FORMAT_DATE)
                    )
            );

        $table
            ->addColumn(
                (new Column())->setLabel('Fin')
                    ->setSort(['e.endDate' => 'asc'])
                    ->setDisplayFormat(Column::FORMAT_DATE)
                    ->setDisplayFormatParams('d/m/Y H:i')
                    ->setFilter((new Filter())
                        ->setField('e.endDate')
                        ->setName('e_endDate')
                        ->setDataFormat(Filter::FORMAT_DATE)
                    )
            );

        $table
            ->addColumn(
                (new Column())->setLabel('DCS')
                    ->setSort(['e.simDcs' => 'asc', 'e.startDate' => 'asc'])
                    ->setFilter((new FilterSelect())
                        ->setField('e.simDcs')
                        ->setName('e_simDcs')
                        ->setChoices($booleanFilterChoices)
                        ->setPlaceholder('-')
                        ->disableTranslation() // disable translations of placeholder and values
                    )
                    ->setDisplayCallback(function ($value, $row, $lines) {
                        if ($value) {
                            return 'oui';
                        } else {
                            return 'non';
                        }
                    })
            );

        $table
            ->addColumn(
                (new Column())->setLabel('BMS')
                    ->setSort(['e.simBms' => 'asc', 'u.nickname' => 'asc'])
                    ->setFilter((new FilterSelect())
                        ->setField('e.simBms')
                        ->setName('e_simBms')
                        ->setChoices($booleanFilterChoices)
                        ->setPlaceholder('-')
                        ->disableTranslation() // disable translations of placeholder and values
                    )
                    ->setDisplayCallback(function ($value, $row, $lines) {
                        if ($value) {
                            return 'oui';
                        } else {
                            return 'non';
                        }
                    })
            );

        $table
            ->addColumn(
                (new Column())->setLabel('Titre')
                    ->setSort(['e.title' => 'asc'])
                    ->setFilter((new Filter())
                        ->setField('e.title')
                        ->setName('e_title')
                    )
            );

        $table
            ->addColumn(
                (new Column())->setLabel('Carte')
                    ->setSort(['map.name' => 'asc'])
                    ->setFilter((new Filter())
                        ->setField('map.name')
                        ->setName('map_name')
                    )
            );

        $table
            ->addColumn(
                (new Column())->setLabel('Module')
                    ->setSort(['module.name' => 'asc'])
                    ->setFilter((new Filter())
                        ->setField('module.name')
                        ->setName('module_name')
                    )
            );

        $table
            ->addColumn(
                (new Column())->setLabel('Restrictions')
                    ->setName('e.restrictions')
                    ->setDisplayCallback(function ($value, $row, $rows) {
                        if (is_object($row['object']) && Event::class === get_class($row['object'])) {
                            /** @var Event $event */
                            $event = $row['object'];

                            $result = '';
                            foreach ($event->getRestrictions() as $restriction) {
                                if ('' != $result) {
                                    $result .= ', ';
                                }
                                $result .= User::getStatusByIdAsString($restriction);
                            }

                            return $result;
                        } else {
                            return '';
                        }
                    })
            );

        $table
            ->addColumn(
                (new Column())->setLabel('Supprimé')
                    ->setSort(['e.deleted' => 'asc'])
                    ->setFilter((new FilterSelect())
                        ->setField('e.deleted')
                        ->setName('e_deleted')
                        ->setChoices($booleanFilterChoices)
                        ->setPlaceholder('-')
                        ->disableTranslation() // disable translations of placeholder and values
                    )
                    ->setDisplayCallback(function ($value, $row, $lines) {
                        if ($value) {
                            return 'oui';
                        } else {
                            return 'non';
                        }
                    })
            );

        return $table;
    }

    /**
     * @Route("", name="admin_calendar_event_list")
     */
    public function list(TableService $tableService): Response
    {
        return $this->render('admin/calendar/event/list.html.twig', [
            'table' => $tableService->createFormView($this->getTable()),
        ]);
    }

    /**
     * @Route("/_ajax", name="admin_calendar_event_list_ajax")
     */
    public function _listAction(TableService $tableService, Request $request)
    {
        return $tableService->handleRequest($this->getTable(), $request);
    }

    /**
     * @Route("/add", name="admin_calendar_event_add")
     * @Route("/{event}/edit", name="admin_calendar_event_edit")
     */
    public function edit(EventManager $eventManager, FileUploaderService $uploaderService, Request $request, Event $event = null): Response
    {
        if (null === $event) {
            $event = new Event();
        }
        $form = $this->createForm(CalendarEventAdminType::class, $event);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $uploadedImage */
            $uploadedImage = $form->get('image')->getData();
            if ($uploadedImage) {
                $imageHeader = $uploaderService->upload($uploadedImage, $this->getUser());
                $event->setImage($imageHeader);
            }

            $eventManager->save($event, true);
            $this->addFlash('success', 'L\'événement a été enregistré');

            return $this->redirectToRoute('admin_calendar_event_view', ['event' => $event->getId()]);
        }

        return $this->render('admin/calendar/event/edit.html.twig', [
            'form' => $form->createView(),
            'event' => $event,
        ]);
    }

    /**
     * @Route("/{event}/delete", name="admin_calendar_event_delete")
     */
    public function delete(EventManager $eventManager, Request $request, Event $event): Response
    {
        $form = $this->createForm(FormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $eventManager->delete($event, true);
            $this->addFlash('success', 'L\'événement a été supprimé');

            return $this->redirectToRoute('admin_calendar_event_list');
        }

        return $this->render('admin/calendar/event/delete.html.twig', [
            'form' => $form->createView(),
            'event' => $event,
        ]);
    }

    /**
     * @Route("/{event}", name="admin_calendar_event_view")
     */
    public function view(Event $event): Response
    {
        return $this->render('admin/calendar/event/view.html.twig', [
            'event' => $event,
            'user' => new User(),
        ]);
    }
}
