<div class="box-body">
     {{--{!! Form::i18nInput('title', trans('product::products.form.title'), $errors, $lang, null,['data-slug'=>'source']) !!}--}}

     <div class='form-group{{$errors->has("$lang.title") ? ' has-error' : ''}}'>
          <div class="row">
               {!! Form::label("{$lang}[title]", trans('product::categories.form.title'),['class'=>'col-md-1']) !!}
               <div class="col-md-11">
                    {!! Form::text("{$lang}[title]", old("$lang.title"),
                 ['class' => 'form-control title', 'placeholder' => trans('product::products.form.title')]) !!}
                    {!! $errors->first("$lang.title", '<span class="help-block">:message</span>') !!}
               </div>
          </div>
     </div>

     <div class='form-group{{ $errors->has("$lang.slug") ? ' has-error' : '' }}'>
          <div class="row">
               {!! Form::label("{$lang}[slug]", trans('product::categories.form.slug'),['class'=>'col-md-1']) !!}
               <div class="col-md-11">
                    {!! Form::text("{$lang}[slug]", old("$lang.slug"), ['class' => 'form-control slug', 'data-slug' => 'target', 'placeholder' => trans('blog::post.form.slug')]) !!}
                    {!! $errors->first("$lang.slug", '<span class="help-block">:message</span>') !!}
               </div>
          </div>
     </div>

     <div class='form-group{{ $errors->has("$lang.keywords") ? ' has-error' : '' }}'>
          <div class="row">
               {!! Form::label("{$lang}[keywords]", trans('product::products.form.keywords'),['class'=>'col-md-1']) !!}
               <div class="col-md-11">
                 {!! Form::text("{$lang}[keywords]", old("$lang.keywords"),
                 ['class' => 'form-control keywords', 'placeholder' => trans('product::products.form.keywords')]) !!}
                 {!! $errors->first("$lang.keywords", '<span class="help-block">:message</span>') !!}
               </div>
          </div>
     </div>

     <div  class='form-group{{ $errors->has("$lang.meta_description") ? ' has-error' : '' }}'>
          <div class="row">
               {!! Form::label("{$lang}[meta_description]", trans('product::products.form.meta_description'),['class'=>'col-md-1']) !!}
               <div class="col-md-11">
                    {!! Form::text("{$lang}[meta_description]", old("$lang.meta_description"),
                         ['class' => 'form-control meta_description', 'placeholder' => trans('product::products.form.meta_description')]) !!}
                    {!! $errors->first("$lang.meta_description", '<span class="help-block">:message</span>') !!}
               </div>
          </div>
     </div>


     {!! Form::i18nTextarea('description', trans('product::products.form.description'), $errors, $lang) !!}
</div>
