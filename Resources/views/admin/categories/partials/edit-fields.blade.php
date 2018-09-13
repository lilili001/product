<div class="box-body">

    <div class='form-group{{ $errors->has("$lang.name") ? ' has-error' : '' }}'>
        <?php $oldName = isset($category->translate($lang)->name) ? $category->translate($lang)->name : ''; ?>
        {!! Form::label("{$lang}[name]", trans('product::categories.form.name')) !!}
        {!! Form::text("{$lang}[name]", old("$lang.name",$oldName), ['class' => 'form-control', 'data-slug' => 'source', 'placeholder' => trans('product::categories.form.name')]) !!}
        {!! $errors->first("$lang.name", '<span class="help-block">:message</span>') !!}
    </div>

    <div class='form-group{{ $errors->has("$lang.title") ? ' has-error' : '' }}'>
        <?php $oldTitle = isset($category->translate($lang)->title) ? $category->translate($lang)->title : ''; ?>
        {!! Form::label("{$lang}[title]", trans('product::categories.form.title')) !!}
        {!! Form::text("{$lang}[title]", old("$lang.title",$oldTitle), ['class' => 'form-control', 'data-slug' => 'source', 'placeholder' => trans('product::categories.form.title')]) !!}
        {!! $errors->first("$lang.title", '<span class="help-block">:message</span>') !!}
    </div>

    <div class='form-group{{ $errors->has("$lang.slug") ? ' has-error' : '' }}'>
        <?php $oldSlug = isset($category->translate($lang)->slug) ? $category->translate($lang)->slug : ''; ?>
        {!! Form::label("{$lang}[slug]", trans('product::categories.form.slug')) !!}
        {!! Form::text("{$lang}[slug]", old("$lang.slug", $oldSlug), ['class' => 'form-control slug', 'data-slug' => 'target', 'placeholder' => trans('blog::post.form.slug')]) !!}
        {!! $errors->first("$lang.slug", '<span class="help-block">:message</span>') !!}
    </div>

    <div class='form-group{{ $errors->has("$lang.key_words") ? ' has-error' : '' }}'>
        <?php $key_words = isset($category->translate($lang)->key_words) ? $category->translate($lang)->key_words : ''; ?>
        {!! Form::label("{$lang}[key_words]", trans('product::categories.form.key_words')) !!}
        {!! Form::textarea("{$lang}[key_words]", old("$lang.key_words",$key_words), ['class' => 'form-control', 'data-slug' => 'source', 'placeholder' => trans('product::categories.form.key_words')]) !!}
        {!! $errors->first("$lang.key_words", '<span class="help-block">:message</span>') !!}
    </div>

    <div class='form-group{{ $errors->has("$lang.meta_description") ? ' has-error' : '' }}'>
        <?php $meta_description = isset($category->translate($lang)->meta_description) ? $category->translate($lang)->meta_description : ''; ?>
        {!! Form::label("{$lang}[meta_description]", trans('product::categories.form.meta_description')) !!}
        {!! Form::textarea("{$lang}[meta_description]", old("$lang.meta_description",$meta_description), ['class' => 'form-control', 'data-slug' => 'source', 'placeholder' => trans('product::categories.form.meta_description')]) !!}
        {!! $errors->first("$lang.meta_description", '<span class="help-block">:message</span>') !!}
    </div>

    <?php $old = isset($category->translate($lang)->description) ? $category->translate($lang)->description : ''; ?>
    @editor('description', trans('product::categories.form.description'), old("{$lang}.description",$old), $lang)


</div>
