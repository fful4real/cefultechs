<?php

namespace App\Controller;

use App\Entity\CeCustomer;
use App\Form\CeCustomerUpdateType;
use App\Form\CustomerType;
use App\Repository\CeCustomerRepository;
use App\Repository\CeTownRepository;
use App\Repository\CeorderRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CustomerController extends AbstractController
{
    /**
     * @Route("/customer", name="customer")
     */
    public function index()
    {
        return $this->render('customer/index.html.twig', [
            'controller_name' => 'CustomerController',
        ]);
    }


    /**
     * @Route("/customer/list", name="customer_list")
     */
    public function customerList(CeorderRepository $orderRepo, CeCustomerRepository $repo, CeCustomerRepository $customerRepo, CeTownRepository $townRepo, Request $reqt)
    {
        $limit = 100;
        $page = intval($reqt->query->get('page')) ?:1;
        $pagination = $orderRepo->getPagination($page,$limit,[],'CeCustomer');
        $offset = ($page - 1)  * $limit;
        $customerData = $repo->getCustomerData($offset,$limit);
        // var_dump($customerData);
        return $this->render('customer/list.html.twig', [
            'pagination'=>$pagination,
            'page'=>$page,
            'customerData'=>$customerData,
        ]);
    }

    /**
     * @Route("/customer/new", name="customer_new")
     * @Route("/customer/{id}/edit", name="customer_edit")
     */

    public function customerForm(CeCustomer $customer = null, Request $reqt, ObjectManager $manager, CeCustomerRepository $customerRepo)
    {
        if (!$customer) {
            $customer = new CeCustomer();
        }

        $customerForm = $this->createForm(CeCustomerUpdateType::class, $customer);
        $customerForm->handleRequest($reqt);


        if ($customerForm->isSubmitted() && $customerForm->isValid()) {
            $postData = $reqt->request;
            $postCustomerData = $reqt->request->get('ce_customer_update');

            if (!$customer->getId()) {
                $customer->setDatec(new \DateTime());
            }
            $postCustomer = $customerRepo->findBy(['mobNum'=>$postCustomerData['mobNum']]);
            $oldCustomer = $customerRepo->find($customer->getId());
            
            if ($postCustomer && $postCustomer[0]->getId() != $customer->getId()) {
                $msg = 'Numer '.$postCustomerData['mobNum'].' Already Exists';
                $this->addFlash('warning', $msg);
                return $this->render('customer/customerform.html.twig', [
                                    'customerForm' => $customerForm->createView(), 
                                    'editMode'=>$customer->getId() ?: null,
                                    'customer'=>$customer,
                                    ]);
                
            }
            $customer->setTms(new \DateTime());

            $manager->persist($customer);

            $manager->flush();
            return $this->redirectToRoute('customer_show',['id'=>$customer->getId()]);

        }

        return $this->render('customer/customerform.html.twig', [
            'customerForm' => $customerForm->createView(), 'editMode'=>$customer->getId() ?: null,
            'customer'=>$customer,
        ]);
    }

    /**
     * @Route("/customer/{id}", name="customer_show")
     */

    public function customerShow(CeCustomer $customer = null)
    {

        return $this->render('customer/show.html.twig', [
            'customer' => $customer,
        ]);
    }
}
