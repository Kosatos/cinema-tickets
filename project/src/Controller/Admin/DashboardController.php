<?php

namespace App\Controller\Admin;

use App\Entity\Admin;
use App\Entity\Cinema;
use App\Entity\Country;
use App\Entity\Hall;
use App\Entity\Seat;
use App\Entity\Session;
use App\Entity\Ticket;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('@EasyAdmin/page/content.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Cinema Club');
    }

    public function configureActions(): Actions
    {
        return parent::configureActions()
            ->update(Crud::PAGE_INDEX, Action::DELETE, fn(Action $action) => $action->setIcon('fa-solid fa-trash-can')->setLabel(false))
            ->update(Crud::PAGE_INDEX, Action::EDIT, fn(Action $action) => $action->setIcon('fa-solid fa-pen-to-square')->setLabel(false));
    }

    public function configureCrud(): Crud
    {
        return parent::configureCrud()
            ->showEntityActionsInlined();
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::section('security');
        yield MenuItem::linkToUrl('Вернуться на сайт', 'fas fa-sitemap', '/');
        yield MenuItem::linkToCrud('Администраторы', 'fa-solid fa-user', Admin::class);

        yield MenuItem::section('кинозал');
        yield MenuItem::linkToCrud('Кино', 'fas fa-video', Cinema::class);
        yield MenuItem::linkToCrud('Страна', 'fas fa-globe', Country::class);
        yield MenuItem::linkToCrud('Зал', 'fas fa-dungeon', Hall::class);
        yield MenuItem::linkToCrud('Место', 'fa-solid fa-chair', Seat::class);
        yield MenuItem::linkToCrud('Сеанс', 'fas fa-film', Session::class);

        yield MenuItem::section('билеты');
        yield MenuItem::linkToCrud('Билеты', 'fas fa-ticket', Ticket::class);
    }
}
