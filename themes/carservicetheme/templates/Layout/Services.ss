<!-- Page Content -->
    <div class="container">

      <!-- Page Heading/Breadcrumbs -->
      <h1 class="mt-4 mb-3">$Title
        <small>Subheading</small>
      </h1>

      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="index.html">Home</a>
        </li>
        <li class="breadcrumb-item active">$Title - $Date.nice</li>
      </ol>

      <!-- Intro Content -->
      <div class="row">
        <div class="col-lg-12">
          <h2>$Title</h2>
          $Content
        $Offered
        $Teaser
        

        <% with $Photo.SetWidth(250) %>
            <img class="custom-class" src="$URL" width="$Width" height="$Height" />
        <% end_with %>

        </div>
      </div>
      <!-- /.row -->

    </div>
    <!-- /.container -->