@extends('layouts.master_admin')

@section('content')

<div class="container">
  <div class="row">
    <div class="col-md-12">
<!-- ERROR / SUCCESS MESSAGE -->
@if(count($errors) > 0)
  <div class="alert">
    <ul>
    @foreach ($errors->all() as $error)
      <li>{{ $error }}</li>
    @endforeach
    </ul>
  </div>
@endif

@if(!empty($status))
  <div class="{{$status['type']}}"> 
  @if($status['type'] == "delete")
    <p>Se ha eliminado "{{$status['name']}}"</p>  
  @elseif($status['type'] == "create")
    <p>Se ha creado "{{$status['name']}}"</p>
  @elseif($status['type'] == "authorize")
    <p>Se ha solicitado la autorización para publicar: "{{$status['name']}}"</p>
  @elseif($status['type'] == "cancel")
    <p>Se canceló la petición de autorización.</p> 
  @else
    <p>Se actualizó "{{$status['name']}}"</p> 
  @endif
  </div>
@endif
<!-- ERROR / SUCCESS MESSAGE -->



    	<h1 class="title">Editar Encuesta</h1>
    </div>
  </div>

  


  <!-- [[   T H E   A P P   ]] -->
  
  <div class="row">
    <!-- [ THE BLUEPRINT ] -->
    <div class="col-sm-4">
	    
	    @if($user->level == 2)
		<!-- THE AUTH BUTTON -->
		<section id="survey-app-title" class="box">
			@if($blueprint->pending)
			<a href="{{url('dashboard/encuestas/cancelar/' . $blueprint->id)}}" class="btn_test preview">cancelar autorización!</a>
			<div class="row">
			    <div class="col-sm-10 col-sm-offset-1">  
			      <p>La encuesta está en proceso de ser autorizada</p>
			    </div>
			</div>     
			@elseif($blueprint->is_public)
			<a href="{{url('dashboard/encuestas/ocultar/' . $blueprint->id)}}" class="btn_test preview">Oculta la encuesta</a>
			<div class="row">
			  <div class="col-sm-10 col-sm-offset-1">
			      <p>Si ocultas la encuesta, ni los resultados ni las preguntas estarán disponibles en línea.</p>
			  </div>
			</div>
			@else
			<a href="{{url('dashboard/encuestas/autorizar/' . $blueprint->id)}}" class="btn_test preview">Publica la encuesta</a>
			<div class="row">
				<div class="col-sm-10 col-sm-offset-1">  
					<p>Un administrador debe autorizar la publicación de la encuesta</p>
				</div>
			</div>
			@endif
  		</section>
  		<!-- THE AUTH BUTTON ENDS -->
  		@endif
	    
	    
      <section id="survey-app-title" class="box">
		<a href="{{url('dashboard/encuesta/test/' . $blueprint->id)}}" class="btn_test preview">Previsualizar encuesta</a>
        <h2>Datos</h2>
        <form id="ubp" name="update-blueprint" action="{{url('dashboard/encuesta/' . $blueprint->id)}}" enctype="multipart/form-data" method="post">
          {!! csrf_field() !!}
          <!-- THE TITLE -->
          <div class="row">
            <div class="col-sm-10 col-sm-offset-1">
              <p><strong>Título</strong></p>
              <p id="js-error-title" class="error"></p>
              <p><input type="text" name="survey-title" value="{{$blueprint->title}}" required></p>
            </div>
          </div>

          <!-- THE PTP URL -->
          <div class="row">
            <div class="col-sm-10 col-sm-offset-1">
              <p><strong>link al PTP</strong></p>
              <p id="js-error-ptp" class="error"></p>
              <p><input type="text" name="survey-ptp" value="{{$blueprint->ptp}}"></p>
            </div>
          </div>
		  
		  <div class="divider"></div>
          <!-- CATEGORY / DEPENDENCY? -->
          <div class="row">
            <div class="col-sm-10 col-sm-offset-1">
              <p><strong>Categoría</strong></p>
              <p id="js-error-category" class="error"></p>
              <p>
                <select name="survey-category" id="survey-category" required>
                  <option value="">Selecciona una categoría</option>
                </select>
              </p>
            </div>

             <!-- SUBCATEGORY -->
            <div class="col-sm-10 col-sm-offset-1">
              <p>Subcategoría</p>
              <p id="js-error-subcategory" class="error"></p>
              <p class="rule">Puedes seleccionar un máximo de 3 subcategorías</p>
              <ul id="sub-list"></ul>
              <!-- survey-tags-->
            </div>

             <!-- TAGS -->
            <div class="col-sm-10 col-sm-offset-1">
              <p>Etiquetas</p>
              <p id="js-error-tags" class="error"></p>
              <p class="rule">Puedes seleccionar un máximo de 5 etiquetas</p>
              <ul id="tag-list"></ul>
              <!-- survey-tags-->
            </div>
          </div>

		  <div class="divider"></div>
          <!-- BANNER -->
          <div class="row">
            <div class="col-sm-10 col-sm-offset-1">
              <p><strong>Selecciona la portada</strong></p>
              <img id="target" src="{{empty($blueprint->banner) ? "": url("img/programas/" . $blueprint->banner) }}" />
              <p><input type="file" name="survey-banner" id="survey-banner"></p>
            </div>
          </div>

          <!-- IS VISIBLE -->
          @if($user->level ==3)
		  <div class="divider"></div>
          <div class="row">
            <div class="col-sm-10 col-sm-offset-1">
              <p><label><input type="checkbox" name="is_public" {{$blueprint->is_public ? 'checked' : ''}}> Es pública</label></p>
            </div>
          </div>
          @endif

		  <div class="divider"></div>
          <!-- IS CLOSED -->
          <div class="row">
            <div class="col-sm-10 col-sm-offset-1">
              <p><label><input type="checkbox" name="is_closed" {{$blueprint->is_closed ? 'checked' : ''}}> Ya terminó</label></p>
            </div>
          </div>

          <!-- THE CSV -->
		  <div class="divider"></div>
          <div class="row">
            <div class="col-sm-10 col-sm-offset-1">
              <p>Sube tus propios resultados</p>
              <p class="rule">Si es una encuesta que ya has aplicado en otra plataforma, publica los resultados
              subiendo el archivo con los mismos. (El archivo estará disponible para descargar la sección de
              resultados)</p>
              <p id="send-file-button"><input type="file" name="results" id="results-file"></p>
            </div>
          </div>
          
		  <div class="divider"></div>
          <!-- SUBMIT -->
          <div class="row">
            <div class="col-sm-10 col-sm-offset-1">
              <p><input type="submit" value="Guardar Cambios"></p>
            </div>
          </div>
        </form>
        
		<div class="divider"></div>
        <!-- CREATE/GET CSV -->
        <div class="row">
          <div class="col-sm-10 col-sm-offset-1">
            
            <!-- GET CSV -->
            @if($blueprint->making_csv == 1)
              <p><a id="get-csv-btn" href="#">Generando CSV</a></p>
              
            @elseif($blueprint->csv_file != '')
              <p><a href="#" class="create-survey-btn">Crear nuevo CSV</a></p>
              <p>
                <a id="get-csv-btn" href="{{url('csv/' . $blueprint->csv_file)}}">descargar Archivo</a>
              </p>
            @else
              <p><a href="#" class="create-survey-btn">Crear CSV</a></p>
              <p><a style="display:none" id="get-csv-btn" href="#">[ CSV PLACEHOLDER ]</a></p>
            @endif
          </div>
        </div>

        <!-- PUT YOUR OWN FILE CSV -->
        <!--
        <div class="row">
          <div class="col-sm-10 col-sm-offset-1">
          <p>Sube tus propios resultados</p>
          <p id="sending-label" style="display:none;">"enviado documento"</p>
          <p id="send-file-button"><input type="file" name="results" id="results-file"></p>
          </div>
        </div>
        -->
      </section>
    </div>
    <!-- { THE BLUEPRINT ENDS } -->


    <form name="survey-app">
    <div class="col-sm-8">  
    <!-- [ THE CONTENT CREATOR ] -->
    <section id="survey-app-questions" class="box">
      <h2>Agregar preguntas</h2>
    <!-- [ ADD CONTENT BUTTONS ] -->
    <div class="row">
      <div class="col-sm-10 col-sm-offset-1">
	  	<p id="survey-add-buttons">
	  	  <a href="#" class="add-question">Agrega pregunta</a> | 
	  	  <a href="#" class="add-text">Agrega texto(HTML)</a>
	  	</p>
      
      <!-- [ NEW QUESTION FORM ] -->
      <div id="survey-add-question" class="new_question" style="display:none">
        <!-- [1] agrega el título -->
        <p>
          <label><strong>Pregunta:</strong></label>
          <input name="question" type="text">
        </p>
        <!-- [1] --> 
        <!-- [2] define el tipo de pregunta -->
        <p>
          <label>la respuesta es:</label>
          <label><input type="radio" name="type" value="text">abierta</label>
          <label><input type="radio" name="type" value="number">numérica</label>
          <label><input type="radio" name="type" value="multiple">opción múltiple</label>
          <label><input type="radio" name="type" value="location">ubicación</label>
        </p>
        <!-- [2] -->
        
        <!-- [4] agrega las respuestas para opción múltiple -->
        <div id="survey-add-options" style="display:none">
          <h4>Opciones de respuesta:</h4>
          <p>Presiona [ ENTER ] para agregar otra respuesta</p>
          <ul>
          </ul>
          <p>
            <label>
              <input type="checkbox" id="keep-options" value="1">
              conservar respuestas
            </label>
          </p>
        </div>
        <!-- [4] -->
        
        <!-- [3] define a la sección a la que pertenece la pregunta -->
        
        <div class="survey-section-selector-container"><!-- weird hack -->
        <p class="survey-section-selector" style="display:none">
          <label><strong>Sección a la que pertenece:</strong>
          <select name="section_id">
            <option value="1" selected>sección 1</option>
            <option value="0">nueva sección</option>
          </select></label>
        </p>
        </div><!-- weird hack ends -->
        <!-- [3] -->
        
        <!-- [5] salva la pregunta -->
        <p><a id="survey-add-question-btn" href="#" class="btn_add">agregar</a></p>
        <!-- [5] -->
      </div>
      <!-- { NEW QUESTION FORM ENDS } -->


      <!-- [ NEW CONTENT FORM ] -->
      <div id="survey-add-content" style="display:none">
        <p><label>HTML:</label></p>
        <p><textarea name="html"></textarea></p>
        <div class="survey-section-selector-container"><!-- weird hack -->
        </div><!-- weird hack ends -->
        <p><a id="survey-add-content-btn" href="#" class="btn_add">Agregar contenido</a></p>
      </div>
      <!-- { NEW CONTENT FORM ENDS } -->
          </div>
        </div>
    </section>
    <!-- { THE CONTENT CREATOR ENDS } -->

    <!-- [ THE SURVEY ] -->
<section id="the-survey" class="box">
  <h2>Preguntas agregadas</h2>
  <div class="row">
    <div class="col-sm-10 col-sm-offset-1">
      
      <!-- [ THE SECTION NAVIGATION ] -->
      <div id="survey-app-navigation" style="display:none">
        <ul id="survey-navigation-menu"></ul>
        

        <!-- [ THE RULES NAVIGATION ] -->
        <div id="survey-navigation-rules-container" style="display:none">
          <h5>Reglas de navegación de la sección</h5>
          <p class="instructions">Selecciona una pregunta y su respuesta para determinar que usuarios verán esta sección</p>
          <p id="survey-add-navigation-rule">
            <select class="select-question">
              <option value="">Selecciona una pregunta</option>
            </select>

            <select class="select-answer" style="display:none">
              <option value="">Selecciona una respuesta</option>
            </select>

            <a href="#" class="add-rule-btn btn_add">Agregar Regla</a>
          </p>
          <ul id="survey-navigation-rules" start="1"></ul>
        </div>


      </div>
      
      <ol id="survey-question-list" start="1"></ol> 
    </div>
  </div>
</section>
    <!-- { THE SURVEY ENDS } -->
    
    </div>
    </div>
  </form>
  <!--    T H E   A P P   E N D S    -->
  
  </div>
</div>

    <!-- THE INITIAL DATA -->
    <script>

      var BASE_PATH  = "{{url('/')}}",
      SurveySettings = {
        blueprint : <?= json_encode($blueprint); ?>,
        questions : <?= json_encode($questions); ?>,
        options   : <?= json_encode($options); ?>,
        rules     : <?= json_encode($rules); ?>
      };

    </script>
    <!-- DEVELOPMENT SOURCE -->
    <script data-main="/js/main.admin" src="/js/bower_components/requirejs/require.js"></script>

@endsection