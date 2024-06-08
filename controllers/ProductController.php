<?php
// ProductController.php
namespace app\controllers;
 
use Yii;
use yii\rest\ActiveController;
use yii\web\Response;
use app\models\Product;
use yii\data\ArrayDataProvider;
use yii\mongodb\Query; 

class ProductController extends ActiveController
{
    public $modelClass = 'app\models\Product';
    // Otras acciones del controlador

 
   
    
    public function actionCreate()
    {
        $model = new Product();
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');

        Yii::$app->response->format = Response::FORMAT_JSON;

        if ($model->save()) {
            Yii::$app->response->statusCode = 201; // Created
            return ['status' => 'success', 'data' => $model];
        } else {
            Yii::$app->response->statusCode = 422; // Unprocessable Entity
            return ['status' => 'error', 'errors' => $model->errors];
        }
    }

    public function actionGetAllProducts()
{
    Yii::$app->response->format = Response::FORMAT_JSON;

    $products = Product::find()->all();

    // Crear un array para almacenar los productos con el campo _id
    $productsWithId = [];
    foreach ($products as $product) {
        $productWithId = $product->attributes; // Obtener los atributos del producto como un array
        $productWithId['_id'] = (string) $product->_id; // Convertir el ObjectID a string
        $productsWithId[] = $productWithId;
    }

    return $productsWithId;
}


public function actionGetProductById()
{
    Yii::$app->response->format = Response::FORMAT_JSON;
    $request = Yii::$app->request;
    $id = $request->post('_id');

    if (!$id) {
        return ['status' => 'error', 'message' => 'El campo _id es requerido.'];
    }

    $product = Product::findOne(['_id' => new \MongoDB\BSON\ObjectID($id)]);

    if (!$product) {
        Yii::$app->response->statusCode = 404; // Not Found
        return ['status' => 'error', 'message' => 'Producto no encontrado.'];
    }

    $productWithId = $product->attributes;
    $productWithId['_id'] = (string) $product->getAttribute('_id');

    return ['status' => 'success', 'data' => $productWithId];
}



public function actionDeleteProductById()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $request = Yii::$app->request;
        $id = $request->post('_id');

        if (!$id) {
            return ['status' => 'error', 'message' => 'El campo _id es requerido.'];
        }

        $product = Product::findOne(['_id' => new \MongoDB\BSON\ObjectID($id)]);

        if (!$product) {
            Yii::$app->response->statusCode = 404; // Not Found
            return ['status' => 'error', 'message' => 'Producto no encontrado.'];
        }

        if ($product->delete()) {
            return ['status' => 'success', 'message' => 'Producto eliminado exitosamente.'];
        } else {
            Yii::$app->response->statusCode = 500; // Internal Server Error
            return ['status' => 'error', 'message' => 'Error al eliminar el producto.'];
        }
    }

     public function actionUpdateProductById()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $request = Yii::$app->request;
        $id = $request->post('_id');
        $name = $request->post('name');
        $price = $request->post('price');

        if (!$id) {
            return ['status' => 'error', 'message' => 'El campo _id es requerido.'];
        }

        $product = Product::findOne(['_id' => new \MongoDB\BSON\ObjectID($id)]);

        if (!$product) {
            Yii::$app->response->statusCode = 404; // Not Found
            return ['status' => 'error', 'message' => 'Producto no encontrado.'];
        }

        if ($name !== null) {
            $product->name = $name;
        }
        if ($price !== null) {
            $product->price = $price;
        }

        if ($product->save()) {
            return ['status' => 'success', 'message' => 'Producto actualizado exitosamente.', 'data' => $product];
        } else {
            Yii::$app->response->statusCode = 422; // Unprocessable Entity
            return ['status' => 'error', 'message' => 'Error al actualizar el producto.', 'errors' => $product->errors];
        }
    }


    public function actionGetProductByIdFromHeader()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $request = Yii::$app->request;
        $id = $request->headers->get('_id');

        if (!$id) {
            return ['status' => 'error', 'message' => 'El campo _id es requerido en el header.'];
        }

        $product = Product::findOne(['_id' => new \MongoDB\BSON\ObjectID($id)]);

        if (!$product) {
            Yii::$app->response->statusCode = 404; // Not Found
            return ['status' => 'error', 'message' => 'Producto no encontrado.'];
        }

        return [
            '_id' => (string) $product->_id,
            'name' => $product->name,
            'price' => $product->price,
        ];
    }
}
