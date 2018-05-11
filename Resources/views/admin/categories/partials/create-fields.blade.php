<div class="box-body">
    <div class='form-group{{ $errors->has("$lang.name") ? ' has-error' : '' }}'>
        {!! Form::label("{$lang}[name]", trans('product::categories.form.name')) !!}
        {!! Form::text("{$lang}[name]", old("$lang.name"), ['class' => 'form-control', 'data-slug' => 'source', 'placeholder' => trans('product::categories.form.name')]) !!}
        {!! $errors->first("$lang.name", '<span class="help-block">:message</span>') !!}
    </div>

    <div class='form-group{{ $errors->has("$lang.title") ? ' has-error' : '' }}'>
        {!! Form::label("{$lang}[title]", trans('product::categories.form.title')) !!}
        {!! Form::text("{$lang}[title]", old("$lang.title"), ['class' => 'form-control', 'data-slug' => 'source', 'placeholder' => trans('product::categories.form.title')]) !!}
        {!! $errors->first("$lang.title", '<span class="help-block">:message</span>') !!}
    </div>

    <div class='form-group{{ $errors->has("$lang.slug") ? ' has-error' : '' }}'>
       {!! Form::label("{$lang}[slug]", trans('product::categories.form.slug')) !!}
       {!! Form::text("{$lang}[slug]", old("$lang.slug"), ['class' => 'form-control slug', 'data-slug' => 'target', 'placeholder' => trans('blog::post.form.slug')]) !!}
       {!! $errors->first("$lang.slug", '<span class="help-block">:message</span>') !!}
    </div>

    <div class='form-group{{ $errors->has("$lang.key_words") ? ' has-error' : '' }}'>
        {!! Form::label("{$lang}[key_words]", trans('product::categories.form.key_words')) !!}
        {!! Form::textarea("{$lang}[key_words]", old("$lang.key_words"), ['class' => 'form-control', 'data-slug' => 'source', 'placeholder' => trans('product::categories.form.key_words')]) !!}
        {!! $errors->first("$lang.key_words", '<span class="help-block">:message</span>') !!}
    </div>

    <div class='form-group{{ $errors->has("$lang.meta_description") ? ' has-error' : '' }}'>
        {!! Form::label("{$lang}[meta_description]", trans('product::categories.form.meta_description')) !!}
        {!! Form::textarea("{$lang}[meta_description]", old("$lang.meta_description"), ['class' => 'form-control', 'data-slug' => 'source', 'placeholder' => trans('product::categories.form.meta_description')]) !!}
        {!! $errors->first("$lang.meta_description", '<span class="help-block">:message</span>') !!}
    </div>
    
    @editor('description', trans('product::categories.form.description'), old("{$lang}.description"), $lang)

</div>
