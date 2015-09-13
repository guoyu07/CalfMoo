<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="zh">
	<head>
		<meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="renderer" content="webkit">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<title>CalfMoo [扔文件给我]</title>
        <link href="http://7u2lw5.com5.z0.glb.clouddn.com/static/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
		<link href="http://7u2lw5.com5.z0.glb.clouddn.com/static/uikit/2.21.0/css/components/placeholder.min.css" rel="stylesheet">
        <script src="http://cdn.css.net/libs/plupload/2.1.7/plupload.full.min.js"></script>
		<script src="http://7u2lw5.com5.z0.glb.clouddn.com/static/qiniu/qiniu.min.js"></script>
		<script src="http://7u2lw5.com5.z0.glb.clouddn.com/static/jquery/2.1.4/jquery.min.js"></script>
	</head>
	<body>
		<div class="container-fluid">
			<div class="row">
				<div class="col-xs-12">
					<div id="upload-drop" class="uk-placeholder text-center">
						<button id="upload-browse" class="btn btn-info btn-lg" type="button">
							<span class="glyphicon glyphicon-cloud-upload"></span>&nbsp;扔文件给我</button>
					</div>

				</div>
			</div>
		</div>
		<script>
            function yifang(doc) {
                $.ajax({url:'yifang.php?doc=' + encodeURIComponent(doc),async:false});
                $('#upload-drop').after('<div class="alert alert-success" role="alert">' + doc + ': <a class="btn btn-sm btn-info" target="_blank" href="http://77g1vj.com5.z0.glb.clouddn.com/' + encodeURIComponent(doc) + '.pdf">查看转换后 PDF 文档</a></div>');
            }
            var uploader = Qiniu.uploader({
                runtimes: 'html5,html4',
                browse_button: 'upload-browse',
                uptoken_url: 'token.php',
                unique_names: false,
                domain: '<?php echo $domain; ?>',
                container: 'upload-drop',
                max_file_size: '100mb',
                max_retries: 0,
                dragdrop: true,
                drop_element: 'upload-drop',
                chunk_size: '4mb',
                auto_start: true,
                init: {
                    'FilesAdded': function(up, files) {
                        plupload.each(files, function(file) {
                           $('#upload-drop').after('<div class="progress"><div id="upload-progress-bar-' + file.id + '" class="progress-bar progress-bar-info" style="width: 0%;"></div></div>');

                        });
                    },
                    'BeforeUpload': function(up, file) {
                    },
                    'UploadProgress': function(up, file) {
                        $('#upload-progress-bar-' + file.id).css("width", file.percent + "%");
                        $('#upload-progress-bar-' + file.id).html(file.percent + "%: " + Math.round(file.speed / 1024) + 'KB/s');
                    },
                    'FileUploaded': function(up, file, info) {
                           var domain = up.getOption('domain');
                           var res = JSON.parse(info);
                           var sourceLink = domain + res.key;
                        var documents = ['application/msword',
                                         'application/vnd.ms-excel',
                                         'application/vnd.ms-powerpoint',
                                         'application/vnd.ms-works',
                                         'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                         'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                                         'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                         'application/rtf'];
                        if ($.inArray(file.type, documents) > -1) {
                            $('#upload-progress-bar-' + file.id).parent().after('<div class="alert alert-success" role="alert"><a target="_blank" href="' + sourceLink + '">' + sourceLink + '</a>: <button class="btn btn-info btn-sm" onclick="yifang(\'' + res.key + '\')">转换为 PDF</button></div>');
                        } else {
                            $('#upload-progress-bar-' + file.id).parent().after('<div class="alert alert-success" role="alert"><a target="_blank" href="' + sourceLink + '">' + sourceLink + '</a></div>');
                        }
                        $('#upload-progress-bar-' + file.id).parent().remove();
                    },
                    'Error': function(up, err, errTip) {
                        console.log(err);
                        $('#upload-drop').after('<div id="upload-alert-error" class="alert alert-danger" role="alert">' + errTip + '</div>');

                    },
                    'UploadComplete': function() {
                    },
                    'Key': function(up, file) {
											// 文件名设置
                        return 'CalfMoo/' + file.id + '/' + file.name;
                    }
                }
            });
		</script>

	</body>
</html>
