    var uploader = WebUploader.create({

        // swf文件路径
        swf:     'https://cdn.staticfile.org/webuploader/0.1.5/Uploader.swf',
        server:  './upload/upload',
        pick:    '#filePicker',
        chunked: true,
        chunkSize: 2*1024*1024,
        // formData: {guid:GUID},
        threads: 1,
        fileNumLimit: 3,
        resize:  false,
        auto: true,
        disableGlobalDnd: true, 
        accept:  {  
                    title: 'Images',  
                    extensions: 'gif,jpg,jpeg,bmp,png,mp4',  
                    mimeTypes: 'image/jpg,image/jpeg,image/png,video/mp4'
                }
    });

    uploader.on( 'uploadSuccess', function( file ,response) {
        console.log(response);
        new Toast({context:$('body'),message:'已上传'}).show();
        send( window.location.origin+'/'+response.url );
    });

    uploader.on( 'uploadError', function( file ) {
        new Toast({context:$('body'),message:'上传出错,请重试'}).show();
    });

