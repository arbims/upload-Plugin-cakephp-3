# upload-Plugin-cakephp-3
Plugin cakephp 3 for upload file 

you need just add this in your classTable 
example
add folder for upload and name Model

```
$this->addBehavior('Upload.Upload', array(
                'fields' => array('image' => 'img/files/'),
                'model' => 'Posts'
            )
        );
````

then add field name with _file in your $_accessible array in your Class Model 

````
'image_file'  => true
````

