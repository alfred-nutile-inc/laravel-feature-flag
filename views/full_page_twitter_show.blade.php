<!DOCTYPE html>
<html>
    <head>
        <title>Laravel</title>

        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                display: table;
                font-weight: 100;
                font-family: 'Lato';
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
            }

            .title {
                font-size: 96px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
              <h2>Twitter Show Example</h2>

              <p>
                When on you see it when off you do not
              </p>

              @can('feature-flag', 'see-twitter-field')
              <div class="row">
                  <div class="col-md-12">
                      <div class="wrapper wrapper-content animated fadeInRight">
                          <div class="ibox">
                              <div class="ibox-title">
                                  <h2>Twitter Name (Feature Flag On If you See This)</h2>
                              </div>
                              <div class="ibox-content">

                                  @if($user->twitter)
                                      <p>Your Twitter Handle is {{ $user->twitter }}</p>
                                  @else
                                      <p>No Twitter Handle here</p>
                                  @endif

                                  <pre>
                                    <?php print_r(\Feature\Feature::isEnabled('see-twitter-field')); ?>
                                  </pre>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
              @endcan

              @cannot('feature-flag', 'see-twitter-field')
               <p>
                 <b>Twitter Flag NOT ON</b>
                 <pre>
                   <?php print_r(\Feature\Feature::isEnabled('see-twitter-field')); ?>
                 </pre>
               </p>
              @endcan
            </div>
        </div>
    </body>
</html>
