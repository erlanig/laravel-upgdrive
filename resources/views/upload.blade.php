<!DOCTYPE html>
<html>
<head>
    <title>Upload Google Drive</title>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <style>
        body{
            font-family:Arial;
            padding:40px;
        }

        .card{
            max-width:500px;
            margin:auto;
            border:1px solid #ddd;
            padding:20px;
            border-radius:10px;
        }

        button{
            padding:10px 20px;
            cursor:pointer;
        }

        #result{
            margin-top:20px;
        }
    </style>
</head>
<body>

<div class="card">

    <h2>Upload File ke Google Drive</h2>

    <input type="file" id="file">

    <br><br>

    <button onclick="uploadFile()">
        Upload
    </button>

    <div id="result"></div>

</div>

<script>

async function uploadFile(){

    const result = document.getElementById('result');
    const button = document.querySelector('button');

    try {

        let file = document.getElementById('file').files[0];

        if (!file) {
            result.innerHTML =
                '<p style="color:red">Pilih file terlebih dahulu</p>';
            return;
        }

        button.disabled = true;
        button.innerText = 'Uploading...';

        result.innerHTML = '<p>Sedang upload...</p>';

        let formData = new FormData();
        formData.append('file', file);

        let res = await axios.post(
            '/upload',
            formData,
            {
                headers:{
                    'Content-Type':'multipart/form-data'
                }
            }
        );

        result.innerHTML = `
            <p><b>Upload Berhasil</b></p>

            <p>
                <a href="${res.data.data.view_url}" target="_blank">
                    Lihat File
                </a>
            </p>

            <p>
                <a href="${res.data.data.download_url}" target="_blank">
                    Download File
                </a>
            </p>
        `;

    } catch(err) {

        console.error(err);

        let message = 'Upload gagal';

        if (err.response?.data?.message) {
            message = err.response.data.message;
        }

        result.innerHTML = `
            <div style="color:red">
                <b>Error:</b><br>
                ${message}
            </div>
        `;

    } finally {

        button.disabled = false;
        button.innerText = 'Upload';

    }
}

</script>

</body>
</html>