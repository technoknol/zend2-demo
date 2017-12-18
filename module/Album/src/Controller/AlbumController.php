<?php

namespace Album\Controller;

// Add the following import:
use Album\Model\AlbumTable;
use function get_class_methods;
use Zend\Authentication\AuthenticationService;
use Zend\Db\Adapter\Adapter;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;
// Add the following import statements at the top of the file:
use Album\Form\AlbumForm;
use Album\Model\Album;


class AlbumController extends AbstractActionController
{
    // Add this property:
    private $table;

    // Add this constructor:
    public function __construct(AlbumTable $table)
    {
        $this->table = $table;
    }

    public function indexAction()
    {
        return new ViewModel([
            'albums' => $this->table->fetchAll(),
        ]);
    }

    public function addAction()
    {
        $form = new AlbumForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();

        if (!$request->isPost()) {
            return ['form' => $form];
        }

        $album = new Album();
        $form->setInputFilter($album->getInputFilter());
        $form->setData($request->getPost());

        if (!$form->isValid()) {
            return ['form' => $form];
        }

        $album->exchangeArray($form->getData());
        $this->table->saveAlbum($album);
        return $this->redirect()->toRoute('album');
    }

    public function editAction()
    {
        $id = $this->params()->fromRoute('id', 0);

        if (0 == $id) {
            return $this->redirect()->toRoute('album', ['action' => 'add']);
        }

        // Retrieve the album with the specified id. Doing so raises
        // an exception if the album is not found, which should result
        // in redirecting to the landing page.

        try {
            $album = $this->table->getAlbum($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('album', ['action' => 'index']);
        }

        $form = new AlbumForm();
        $form->bind($album);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        $viewData = ['id' => $id, 'form' => $form];

        if(!$request->isPost()){
            return $viewData;
        }

        $form->setInputFilter($album->getInputFilter());
        $form->setData($request->getPost());

        if(! $form->isValid()){
            return $viewData;
        }

        $this->table->saveAlbum($album);

        return $this->redirect()->toRoute('album', ['action' => 'index']);
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('album');
        }

        $request = $this->getRequest();
//        echo "<pre>";
//        print_r($request);
//        print_r(get_class_methods($request));
//        die();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->table->deleteAlbum($id);
            }

            // Redirect to list of albums
            return $this->redirect()->toRoute('album');
        }

        return [
            'id'    => $id,
            'album' => $this->table->getAlbum($id),
        ];
    }

    public function sessionAction() {
        $c = new Container();
        if (!isset($c->count)) {
            $c->count = 0;
        } else {
            $c->count++;
        }
//        echo '<pre>';
//        print_r(get_class_methods($c));
//        print_r(($c->getName()));
//        die;

        $view = new ViewModel([
            'count' => $c->count,
        ]);
        return $view;

    }
    public function loginAction() {
//        $config = $container->get('config');
        $adap = new Adapter('lkajsdf', 'askjdfh');
        $auth = new AuthenticationService();

        $result = $auth->authenticate($adap);
        echo '<pre>';
//        print_r(get_class_methods($c));
        print_r(($result));
        die;

        if($result->isValid) {
            $identity = $auth->getIdentity();
        } else {
            // process $result->getMessages()
        }
// clear
        $auth->clearIdentity();

        $view = new ViewModel([
            'count' => $c->count,
        ]);
        return $view;

    }
}