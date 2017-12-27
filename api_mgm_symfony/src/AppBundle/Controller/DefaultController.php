<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use AppBundle\Service\CredentialsCheck;
use AppBundle\Entity\Item;
use AppBundle\Entity\ItemList;
use AppBundle\Entity\Param;
use AppBundle\Entity\ParamsTypeChoice;
use AppBundle\Entity\ParamsTypeInt;
use AppBundle\Entity\ParamsTypeDate;

class DefaultController extends FOSRestController
{

    //Fonction de l'api qui créé des Users (exporter maximum de logique dans les services)


    /**
     * @Rest\Post("api/create")
     */
    public function createUserAction(Request $request, CredentialsCheck $credentialsCheck)
    {

        $email = $request->get('email');
        $username = $request->get('username');
        $password = $request->get('password');

        $user = $credentialsCheck->checkLogin($username);

        if($user){
            $response = new JsonResponse();
            $response->setData(json_encode(array("result"=>"failure")));
            return $response;
        }
        else{

            $userManager = $this->get('fos_user.user_manager');

            $user = $userManager->createUser();
            $user->setUsername($username);
            $user->setEmail($email);
            $user->setEnabled(true);
            $user->setPlainPassword($password);
            $userManager->updateUser($user, true);



            $response = new JsonResponse();
            $response->setData(json_encode(array(
                "result"=>"success",
                "userid"=> $user->getId(),
                "username"=>$user->getUsername(),
                "useremail"=> $user->getEmail(),
                "userpw"=>$password,
                "sessionStoarge"=>true)));
            return $response;
        }
    }

    /**
     * @Rest\Post("api/connect")
     */
    public function authenticateUserAction(Request $request, CredentialsCheck $credentialsCheck)
    {
        $username = $request->get('username');
        $password = $request->get('password');

        $response = new JsonResponse();

        $user = $credentialsCheck->checkLogin($username);

        if($user){
            $passwordMatch = $credentialsCheck->checkPassword($user,$password);

            if ($passwordMatch === "true") {
                $responseContent = array("result"=>"success", "userid"=>$user->getId(), "username"=>$user->getUsername(),"useremail"=> $user->getEmail(),"userpw"=>$password, "sessionStorage"=>true);
            }
            else {
                $responseContent = array("result"=>"failure");
            }

            $response->setData(json_encode($responseContent));
        }
        else{
            $response->setData(json_encode(array('result'=> "failure")));
        }

        return $response;
    }

    /**
     * @Rest\Get("api/itemsListByUser")
     */
    public function findItemListByUser(Request $request, CredentialsCheck $credentialsCheck)
    {
        $username = $request->get('username');
        $password = $request->get('password');
        $userid = $request->get('userid');

        $user = $credentialsCheck->checkLogin($username);

        if($user) {
            $passwordMatch = $credentialsCheck->checkPassword($user, $password);
            if($passwordMatch === "true"){
                $db = $this->getDoctrine()->getManager();
                $listRepo =  $db->getRepository('AppBundle:ItemList');
                $userListsResponse = $listRepo->getArrayOfListsByUser($userid);

                return new jsonResponse(json_encode(array("result"=>"success", "list"=>json_encode($userListsResponse))));
            }
            else{
                return new jsonResponse(json_encode(array("result"=>"failure")));
            }
        }
        else{
            return new jsonResponse(json_encode(array("result"=>"failure")));
        }

    }

    /**
     * @Rest\Get("api/itemsByList")
     */
    public function findItemsByList(Request $request, CredentialsCheck $credentialsCheck)
    {
        $username = $request->get('username');
        $password = $request->get('password');
        $userid = $request->get('userid');
        $listid = $request->get('listid');

        $user = $credentialsCheck->checkLogin($username);


        if($user) {
            $passwordMatch = $credentialsCheck->checkPassword($user, $password);
            if($passwordMatch === "true"){
                $db = $this->getDoctrine()->getManager();
                $listRepo =  $db->getRepository('AppBundle:Item');
                $userListsResponse = $listRepo->getArrayOfItemsByList($listid);

                return new jsonResponse(json_encode(array("result"=>"success", "items"=>json_encode($userListsResponse))));
            }
            else{
                return new jsonResponse(json_encode(array("result"=>"failure")));
            }
        }
        else{
            return new jsonResponse(json_encode(array("result"=>"failure")));
        }

    }
    /**
     * @Rest\Get("api/listById")
     */
    public function getListById(Request $request, CredentialsCheck $credentialsCheck)
    {

        $username = $request->get('username');
        $password = $request->get('password');
        $listid = $request->get('listid');


        $user = $credentialsCheck->checkLogin($username);


        if($user) {
            $passwordMatch = $credentialsCheck->checkPassword($user, $password);
            if($passwordMatch === "true"){

                $db = $this->getDoctrine()->getManager();
                $list = $db->getRepository('AppBundle:ItemList')->getArrayListById($listid);

                return new jsonResponse(json_encode(array("result"=>"success", "list" => json_encode($list))));
            }
            else{
                return new jsonResponse(json_encode(array("result"=>"failure")));
            }
        }
        else{
            return new jsonResponse(json_encode(array("result"=>"failure")));
        }

    }
    /**
     * @Rest\Post("api/createList")
     */
    public function createNewList(Request $request, CredentialsCheck $credentialsCheck)
    {
        $username = $request->request->get('username');
        $password = $request->request->get('password');
        $listname = $request->request->get('listname');

        $file = $request->files->get('listimg');
        $fileName = md5(uniqid()).'.'.$file->guessExtension();
        $file->move(
            $this->getParameter('files_directory'),
            $fileName
        );

        $user = $credentialsCheck->checkLogin($username);


        if($user) {
            $passwordMatch = $credentialsCheck->checkPassword($user, $password);
            if($passwordMatch === "true"){

                $db = $this->getDoctrine()->getManager();
                $newList = new ItemList();
                $newList->setName($listname);
                $newList->setDate(new \DateTime());
                $newList->setImg($fileName);
                $newList->setUser($user);

                $db->persist($newList);
                $db->flush();

                return new jsonResponse(json_encode(array("result"=>"success")));
            }
            else{
                return new jsonResponse(json_encode(array("result"=>"failure")));
            }
        }
        else{
            return new jsonResponse(json_encode(array("result"=>"failure")));
        }
    }

    /**
     * @Rest\Post("api/updateList")
     */
    public function updateList(Request $request, CredentialsCheck $credentialsCheck)
    {

        $username = $request->request->get('username');
        $password = $request->request->get('password');
        $listname = $request->request->get('listname');
        $listid = $request->request->get('itemListId');

        $file = $request->files->get('listimg');
        $fileName = md5(uniqid()).'.'.$file->guessExtension();
        $file->move(
            $this->getParameter('files_directory'),
            $fileName
        );
        $user = $credentialsCheck->checkLogin($username);


        if($user) {
            $passwordMatch = $credentialsCheck->checkPassword($user, $password);
            if($passwordMatch === "true"){

                $db = $this->getDoctrine()->getManager();
                $list = $db->getRepository('AppBundle:ItemList')->findOneById($listid);

                $list->setName($listname);
                $list->setImg($fileName);
                $list->setUser($user);

                $db->persist($list);
                $db->flush();

                return new jsonResponse(json_encode(array("result"=>"success")));
            }
            else{
                return new jsonResponse(json_encode(array("result"=>"failure")));
            }
        }
        else{
            return new jsonResponse(json_encode(array("result"=>"failure")));
        }
    }


    /**
     * @Rest\Delete("api/deleteList")
     */
    public function deleteExistingList(Request $request, CredentialsCheck $credentialsCheck)
    {

        $username = $request->get('username');
        $password = $request->get('password');
        $listid = $request->get('itemListId');


        $user = $credentialsCheck->checkLogin($username);


        if($user) {
            $passwordMatch = $credentialsCheck->checkPassword($user, $password);
            if($passwordMatch === "true"){

                $db = $this->getDoctrine()->getManager();
                $repo = $db->getRepository('AppBundle:ItemList');
                $list = $repo->findOneById($listid);
                $db->remove($list);
                $db->flush();

                return new jsonResponse(json_encode(array("result"=>"success")));
            }
            else{
                return new jsonResponse(json_encode(array("result"=>"failure1")));
            }
        }
        else{
            return new jsonResponse(json_encode(array("result"=>"failure2")));
        }

    }


    /**
     * @Rest\Post("api/addItem")
     */
    public function addItemTolist(Request $request, CredentialsCheck $credentialsCheck)
    {

        $username = $request->request->get('username');
        $password = $request->request->get('password');
        $itemListId = $request->request->get('itemListId');
        $itemname = $request->request->get('itemname');
        $itemDescription = $request->request->get('description');
        $itemPrice = $request->request->get('price');
        $params = json_decode($request->request->get('newParams'));
        $file = $request->files->get('itemimg');
        $fileName = md5(uniqid()).'.'.$file->guessExtension();
        $file->move(
            $this->getParameter('files_directory'),
            $fileName
        );
        $user = $credentialsCheck->checkLogin($username);

        if($user) {
            $passwordMatch = $credentialsCheck->checkPassword($user, $password);
            if($passwordMatch === "true"){

                $db = $this->getDoctrine()->getManager();
                $itemList = $db->getRepository('AppBundle:ItemList')->findOneById($itemListId);

                $newItem = new Item();
                $newItem->setName($itemname);
                $newItem->setDate(new \DateTime());
                $newItem->setImg($fileName);
                $newItem->setDescription($itemDescription);
                $newItem->setPrice($itemPrice);
                $newItem->setItemList($itemList);
                $newItem->setCompleted(false);

                foreach ($params as $index => $param) {
                    $paramParent = $db->getRepository('AppBundle:Param')->findOneById($param->id);
                    if ($param->type ===  "choice" && $param->value !== '') {
                        $paramTypeChoice = $db->getRepository('AppBundle:ParamsTypeChoice')->findOneById($param->value);
                        $newItem->addParamsTypeChoice($paramTypeChoice);
                    }
                    elseif ($param->type === "date" && $param->value !== '') {
                        $paramTypeDate = new ParamsTypeDate();
                        $paramTypeDate->setParam($paramParent);
                        $paramTypeDate->setDateValue(new \DateTime($param->value));
                        $db->persist($paramTypeDate);
                        $newItem->addParamsTypeDate($paramTypeDate);
                    }
                    elseif ($param->type === "int" && $param->value !== '') {
                        $paramTypeInt = new ParamsTypeInt();
                        $paramTypeInt->setParam($paramParent);
                        $paramTypeInt->setIntValue($param->value);
                        $db->persist($paramTypeInt);
                        $newItem->addParamsTypeInt($paramTypeInt);
                    }
                }
                $db->persist($newItem);
                $db->flush();

                return new jsonResponse(json_encode(array("result"=>"success")));
            }
            else{
                return new jsonResponse(json_encode(array("result"=>"failure")));
            }
        }
        else{
            return new jsonResponse(json_encode(array("result"=>"failure")));
        }
    }


    /**
     * @Rest\Get("api/itemById")
     */
    public function getItemById(Request $request, CredentialsCheck $credentialsCheck)
    {

        $username = $request->get('username');
        $password = $request->get('password');
        $itemid = $request->get('itemid');

        $user = $credentialsCheck->checkLogin($username);

        if($user) {
            $passwordMatch = $credentialsCheck->checkPassword($user, $password);
            if($passwordMatch === "true"){

                $db = $this->getDoctrine()->getManager();
                $item = $db->getRepository('AppBundle:Item')->getArrayItemById($itemid);

                return new jsonResponse(json_encode(array("result"=>"success", "item" => json_encode($item))));
            }
            else{
                return new jsonResponse(json_encode(array("result"=>"failure")));
            }
        }
        else{
            return new jsonResponse(json_encode(array("result"=>"failure")));
        }

    }

    /**
     * @Rest\Post("api/updateItem")
     */
    public function updateItem(Request $request, CredentialsCheck $credentialsCheck)
    {

        $username = $request->request->get('username');
        $password = $request->request->get('password');
        $itemname = $request->request->get('itemname');
        $itemDescription = $request->request->get('description');
        $itemPrice = $request->request->get('price');
        $itemId = $request->request->get('itemId');
        $params = json_decode($request->request->get('newParams'));

        $file = $request->files->get('itemimg');
        $fileName = md5(uniqid()).'.'.$file->guessExtension();
        $file->move(
            $this->getParameter('files_directory'),
            $fileName
        );

        $user = $credentialsCheck->checkLogin($username);

        if($user) {
            $passwordMatch = $credentialsCheck->checkPassword($user, $password);
            if($passwordMatch === "true"){

                $db = $this->getDoctrine()->getManager();

                $item = $db->getRepository('AppBundle:Item')->findOneById($itemId);
                $item->setName($itemname);
//                $item->setDate(new \DateTime());
                $item->setImg($fileName);
                $item->setDescription($itemDescription);
                $item->setPrice($itemPrice);



                foreach ($params as $index => $param) {

                    $paramParent = $db->getRepository('AppBundle:Param')->findOneById($param->id);

                    if ($param->type ===  "choice" && $param->value !== '') {
                        $choices = $item->getParamsTypeChoice();
                        $id = $param->value;
                        $paramTypeChoice = $db->getRepository('AppBundle:ParamsTypeChoice')->findOneById($id);

                        foreach ($choices as $ind => $choice) {
                            if ($choice->getParam() === $paramTypeChoice->getParam() ) {
                                $item->removeParamsTypeChoice($choice);
                            }
                        }
                        $item->addParamsTypeChoice($paramTypeChoice);
                    }
                    if ($param->type === "date" && $param->value !== '') {
                        $dates = $item->getParamsTypeDate();
                        $paramTypeDate = $dates[0];

                        if ($paramTypeDate) {
                            $paramTypeDate->setDateValue(new \DateTime($param->value));
                            $db->persist($paramTypeDate);
                        }
                        else{
                            $paramTypeDate = new ParamsTypeDate();
                            $paramTypeDate->setParam($paramParent);
                            $paramTypeDate->setDateValue(new \DateTime($param->value));
                            $db->persist($paramTypeDate);
                            $item->addParamsTypeDate($paramTypeDate);
                        }

                    }
                    if ($param->type === "int" && $param->value !== '') {
                        $ints = $item->getParamsTypeInt();
                        $paramTypeInt = $ints[0];
                        if ($paramTypeInt) {
                            $paramTypeInt->setIntValue($param->value);
                            $db->persist($paramTypeInt);
                        } else {
                            $paramTypeInt = new ParamsTypeInt();
                            $paramTypeInt->setParam($paramParent);
                            $paramTypeInt->setIntValue($param->value);
                            $db->persist($paramTypeInt);
                            $item->addParamsTypeInt($paramTypeInt);

                        }
                    }
                }
                $db->persist($item);
                $db->flush();

                return new jsonResponse(json_encode(array("result"=>"success")));
            }
            else{
                return new jsonResponse(json_encode(array("result"=>"failure")));
            }
        }
        else{
            return new jsonResponse(json_encode(array("result"=>"failure")));
        }
    }

    /**
     * @Rest\Delete("api/deleteItem")
     */
    public function deleteItemInlist(Request $request, CredentialsCheck $credentialsCheck)
    {
        $username = $request->get('username');
        $password = $request->get('password');
        $itemid = $request->get('itemId');


        $user = $credentialsCheck->checkLogin($username);


        if($user) {
            $passwordMatch = $credentialsCheck->checkPassword($user, $password);
            if($passwordMatch === "true"){

                $db = $this->getDoctrine()->getManager();
                $repo = $db->getRepository('AppBundle:Item');
                $item = $repo->findOneById($itemid);
                $db->remove($item);
                $db->flush();

                return new jsonResponse(json_encode(array("result"=>"success")));
            }
            else{
                return new jsonResponse(json_encode(array("result"=>"failure1")));
            }
        }
        else{
            return new jsonResponse(json_encode(array("result"=>"failure2")));
        }
    }

    /**
     * @Rest\Put("/completed")
     */
    public function itemComplted(Request $request, CredentialsCheck $credentialsCheck)
    {
        $username = $request->get('username');
        $password = $request->get('password');
        $itemid = $request->get('itemId');


        $user = $credentialsCheck->checkLogin($username);


        if($user) {
            $passwordMatch = $credentialsCheck->checkPassword($user, $password);
            if($passwordMatch === "true"){

                $db = $this->getDoctrine()->getManager();
                $repo = $db->getRepository('AppBundle:Item');
                $item = $repo->findOneById($itemid);

                $completionStatus = $item->getCompleted();

                if ($completionStatus) {
                    $item->setCompleted(false);
                }
                else {
                    $item->setCompleted(true);
                }

                $db->persist($item);
                $db->flush();

                return new jsonResponse(json_encode(array("result"=>"success")));
            }
            else{
                return new jsonResponse(json_encode(array("result"=>"failure1")));
            }
        }
        else{
            return new jsonResponse(json_encode(array("result"=>"failure2")));
        }
    }
    /**
     * @Rest\Post("api/addParams")
     */
    public function addParams(Request $request, CredentialsCheck $credentialsCheck)
    {
        $username = $request->get('username');
        $password = $request->get('password');
        $listId = $request->get('listId');
        $paramName = $request->get('paramName');
        $paramType  = $request->get('paramType');
        $dateB  = $request->get('dateB');
        $dateE  = $request->get('dateE');
        $intMax  = $request->get('intMax');
        $intMin  = $request->get('intMin');


        $user = $credentialsCheck->checkLogin($username);


        if($user) {
            $passwordMatch = $credentialsCheck->checkPassword($user, $password);
            if($passwordMatch === "true"){

                $db = $this->getDoctrine()->getManager();
                $repo = $db->getRepository("AppBundle:ItemList");
                $list = $repo->findOneById($listId);

                $param = new Param();
                $param->setName($paramName);
                $param->setType($paramType);
                if ($paramType === "date") {

                    $dateB = $dateB === "" ? null : new \DateTime($dateB);
                    $dateE = $dateE === "" ? null : new \DateTime($dateE);

                    $param->setDateMin($dateB);
                    $param->setDateMax($dateE);
                    $param->setIntMin(null);
                    $param->setIntMax(null);
                }
                elseif ($paramType === "int") {

                    $intMax = $intMax === "" ? null : $intMax;
                    $intMin = $intMin === "" ? null : $intMin;

                    $param->setDateMin(null);
                    $param->setDateMax(null);
                    $param->setIntMin($intMin);
                    $param->setIntMax($intMax);
                }
                else{
                    $param->setDateMin(null);
                    $param->setDateMax(null);
                    $param->setIntMin(null);
                    $param->setIntMax(null);
                }
                $param->setItemList($list);

                $db->persist($param);

                if ($paramType === "choice") {
                    $choices = $request->get('choices');

                    $choices = json_decode($choices);

                    foreach ($choices as $index => $choice) {
                        $paramChoice = new ParamsTypeChoice();
                        $paramChoice->setName($choice->inputValue);
                        $paramChoice->setParam($param);
                        $db->persist($paramChoice);
                    }
                }

                $db->flush();

                return new jsonResponse(json_encode(array("result"=>"success")));

            }
            else{
                return new jsonResponse(json_encode(array("result"=>"failure2")));
            }
        }
        else{
            return new jsonResponse(json_encode(array("result"=>"failure3")));
        }
    }
    /**
     * @Rest\Get("api/getParams")
     */
    public function getParamsById(Request $request, CredentialsCheck $credentialsCheck)
    {
        $username = $request->get('username');
        $password = $request->get('password');
        $listId = $request->get('listId');



        $user = $credentialsCheck->checkLogin($username);


        if($user) {
            $passwordMatch = $credentialsCheck->checkPassword($user, $password);
            if($passwordMatch === "true"){

                $db = $this->getDoctrine()->getManager();
                $repo = $db->getRepository("AppBundle:Param");
                $params = $repo->getArrayOfParamsByList($listId);

                foreach ($params as $index => $param) {
                    foreach ($param["paramsTypeChoice"] as $i => $typeChoice) {
                        $typeChoice["model"]= false;
                    }
                    foreach ($param["paramsTypeDate"] as $inde => $typeDate) {
                        $typeDate["model"]= false;
                    }
                    foreach ($param["paramsTypeInt"] as $ind => $typeInt) {
                        $typeInt["model"]= false;
                    }
                }
                return new JsonResponse(json_encode(array("result"=>"success", "params"=>json_encode($params))));
            }
            else{
                return new jsonResponse(json_encode(array("result"=>"failure1")));
            }
        }
        else{
            return new jsonResponse(json_encode(array("result"=>"failure2")));
        }
    }
}
