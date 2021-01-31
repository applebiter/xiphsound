<div class="page-header" id="banner">
  <div class="row">
    <div class="col-lg-8 col-md-7 col-sm-6">
      <h1>Add Sounds to the Database</h1>
    </div>
    <div class="col-lg-4 col-md-5 col-sm-6">
      <div class="sponsor">
      
      </div>
    </div>
  </div>
</div>

<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="/sounds">Sounds</a></li>
  <li class="breadcrumb-item active">Add</li>
</ol>

<div class="bs-docs-section">
    <div class="row">
      <div class="col-lg-4">
        <ul>
          <li>
            <a href="/sounds/scandir">
              Scan a directory on the server for FLAC and Ogg Vorbis files
            </a><br/><br/>
          </li>
          <li>
            <a href="/sounds/upload">
              Upload one or more FLAC or Ogg Vorbis files
            </a>
          </li>        
        </ul>
      </div>
      <div class="col-lg-8">
        <p class="lead text-info">
          There are currently two ways to get your music files scanned into the 
          database. The first way is to point to an existing folder on the 
          server containing valid audio file types, and let the application scan 
          them. The second way is to upload your audio files to your server 
          using the file upload functionality of this application.
        </p> 
        <p> 
          The way I use this application is on my Ubuntu desktop PC, which 
          already has my music collection stored in my Music directory. The app 
          is available from other devices on my home network, and so I could 
          upload more files to it from another device if there isn't a better 
          way to tranfer those files.
        </p> 
        <p> 
          If you want to install this on a server that does not already contain 
          your audio files and you have a lot of them to transfer, it would be 
          better to use SSH, SFTP, FTP, or NFS to transfer them than to use the 
          built-in upload feature of this app. It's just not built for that so 
          much as for uploading an album at a time.
        </p> 
      </div>
    </div>
</div>
