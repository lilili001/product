<div class="box-body">
    <div class='form-group{{ $errors->has("$lang.name") ? ' has-error' : '' }}'>
        <?php $oldName = isset($attrset->translate($lang)->name) ? $attrset->translate($lang)->name : ''; ?>
        {!! Form::label("{$lang}[name]", trans('product::attrsets.form.name')) !!}
        {!! Form::text("{$lang}[name]", old("$lang.name",$oldName), ['class' => 'form-control', 'data-slug' => 'source', 'placeholder' => trans('product::attrsets.form.name')]) !!}
        {!! $errors->first("$lang.name", '<span class="help-block">:message</span>') !!}
    </div>
</div>
