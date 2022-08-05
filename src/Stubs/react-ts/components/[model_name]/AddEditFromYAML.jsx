import { useRouter } from 'next/router';
import { useState } from 'react';
import { useForm, useFormState } from 'react-hook-form';
import { yupResolver } from '@hookform/resolvers/yup';
import * as Yup from 'yup';

import { Link } from 'components/Link';
import { userService } from '/services/userService';
import { alertService } from '/services/alertService';
import crudConfig from 'user.yml';

import { getStyleObjectFromString } from "helpers/stringHelper";


export { AddEditFromYAML };

function AddEditFromYAML(props) {
  const user = props?.user;
  const isAddMode = !user;
  const router = useRouter();
  const [showPassword, setShowPassword] = useState(false);

  // form validation rules
  const validationSchema = Yup.object().shape({
    id: Yup.string()
      .required('IDは入力必須です'),
    title: Yup.string()
      .required('Title is required'),
    firstName: Yup.string()
      .required('First Name is required'),
    lastName: Yup.string()
      .required('Last Name is required'),
    email: Yup.string()
      .email('Email is invalid')
      .required('Email is required'),
    role: Yup.string()
      .required('Role is required'),
    password: Yup.string()
      .transform(x => x === '' ? undefined : x)
      .concat(isAddMode ? Yup.string().required('Password is required') : null)
      .min(6, 'Password must be at least 6 characters'),
    confirmPassword: Yup.string()
      .transform(x => x === '' ? undefined : x)
      .when('password', (password, schema) => {
        if (password || isAddMode) return schema.required('Confirm Password is required');
      })
      .oneOf([Yup.ref('password')], 'Passwords must match')
  });
  const formOptions = { resolver: yupResolver(validationSchema) };

  // set default form values if in edit mode
  if (!isAddMode) {
    const { password, confirmPassword, ...defaultValues } = user;
    formOptions.defaultValues = defaultValues;
  }

  // get functions to build form with useForm() hook
  const { register, handleSubmit, reset, formState } = useForm(formOptions);
  const { errors, isDirty, isValid } = formState;

  function onSubmit(data) {
    return isAddMode
      ? createUser(data)
      : updateUser(user.id, data);
  }

  function createUser(data) {
    return userService.create(data)
      .then(() => {
        alertService.success('User added', { keepAfterRouteChange: true });
        router.push('.');
      })
      .catch(alertService.error);
  }

  function updateUser(id, data) {
    return userService.update(id, data)
      .then(() => {
        alertService.success('User updated', { keepAfterRouteChange: true });
        router.push('..');
      })
      .catch(alertService.error);
  }


  /**
   * フォームの valid クラス名を返す
   *
   */
  function getValidOrInvalidClassname(form_name) {
     if ( errors[form_name] ){
       return ' is-invalid';
     }
     else if(formState.dirtyFields[form_name]){
      return ' is-valid';
     }
     else {
       return '';
     }
  }


  /**
   * htmlフォーム出力
   *
   */
  function renderHTMLWithInputType(config_row, config) {

    const form_name = config_row.name;
    const isViewColumnNameInEdit = config.view_column_name_in_edit === 1;

    if (config_row.input_type === 'text') {
      return (
        <>
          <label>
            {config_row.view_list_title}
            {isViewColumnNameInEdit && <span style={{ fontSize: '95%', paddingLeft: '15px', opacity: '0.5' }}>( {form_name} )</span>}
          </label>
          <input type="text" {...register(form_name)} className={'form-control' + getValidOrInvalidClassname(form_name)}
          style={getStyleObjectFromString(config_row.input_css_style)}
          />
        </>

      )
    }
    else if (config_row.input_type === 'textarea') {
      return (
        <>
          <label>
            {config_row.view_list_title}
            {isViewColumnNameInEdit && <span style={{ fontSize: '95%', paddingLeft: '15px', opacity: '0.5' }}>( {form_name} )</span>}
          </label>
          <textarea name="firstName" type="text" {...register('firstName')} className={`form-control ${errors.firstName ? 'is-invalid' : ''}`}
            style={getStyleObjectFromString(config_row.input_css_style)}
          />
        </>
      )
    }
    else if (config_row.input_type === 'select') {
      return (
        <>
          <label>
            {config_row.view_list_title}
            {isViewColumnNameInEdit && <span style={{ fontSize: '95%', paddingLeft: '15px', opacity: '0.5' }}>( {form_name} )</span>}
          </label>
          <select name="title" {...register('title')} className={`form-control ${errors.title ? 'is-invalid' : ''}`}>
            {Object.keys(config_row.input_values_array).map(key => {
              return <option value="{key}">{config_row.input_values_array[key]}</option>
            })}
          </select>
        </>
      )
    }
    else if (config_row.input_type === 'checkbox') {
      return (
        <>
          <label>
            {config_row.view_list_title}
            {isViewColumnNameInEdit && <span style={{ fontSize: '95%', paddingLeft: '15px', opacity: '0.5' }}>( {form_name} )</span>}
          </label>
          <div>
            <label>
              <input type="checkbox" value={config_row.input_checked_value} />
              <span style={{ paddingLeft: '5px' }}>
                {config_row.input_label}
              </span>
            </label>
          </div>
        </>
      )
    }
    else {
      return <span style={{ color: "red", fontWeight: "bold" }}>コンポーネント未作成( {config_row.input_type} )</span>
    }
  }


  return (
    <form onSubmit={handleSubmit(onSubmit)}>
      <h1>{isAddMode ? 'Add User' : 'Edit User'}</h1>
      {Object.keys(crudConfig.table_desc).map(key => {
        const form_name = crudConfig.table_desc[key].name;

        if (crudConfig.table_desc[key]['view_edit_flag'] == 1)
          return (
            <div className="form-row">
              <div className="form-group col">
                {renderHTMLWithInputType(crudConfig.table_desc[key], crudConfig)}
                <div className="invalid-feedback">{errors[form_name]?.message}</div>
              </div>
            </div>

          )
      })}


      <div className="form-row">
        <div className="form-group col-5">
          <label>First Name</label>
          <input name="firstName" type="text" {...register('firstName')} className={`form-control ${errors.firstName ? 'is-invalid' : ''}`} />
          <div className="invalid-feedback">{errors.firstName?.message}</div>
        </div>
        <div className="form-group col-5">
          <label>Last Name</label>
          <input name="lastName" type="text" {...register('lastName')} className={`form-control ${errors.lastName ? 'is-invalid' : ''}`} />
          <div className="invalid-feedback">{errors.lastName?.message}</div>
        </div>
      </div>
      {/*
      <div className="form-row">
        <div className="form-group col-7">
          <label>Email</label>
          <input name="email" type="text" {...register('email')} className={`form-control ${errors.email ? 'is-invalid' : ''}`} />
          <div className="invalid-feedback">{errors.email?.message}</div>
        </div>
        <div className="form-group col">
          <label>Role</label>
          <select name="role" {...register('role')} className={`form-control ${errors.role ? 'is-invalid' : ''}`}>
            <option value=""></option>
            <option value="User">User</option>
            <option value="Admin">Admin</option>
          </select>
          <div className="invalid-feedback">{errors.role?.message}</div>
        </div>
      </div>
 */}

      <div className="form-row">
        <div className="form-group col">
          <label>
            Password
            {!isAddMode &&
              (!showPassword
                ? <span> - <a onClick={() => setShowPassword(!showPassword)} className="text-primary">Show</a></span>
                : <em> - {user.password}</em>
              )
            }
          </label>
          <input name="password" type="password" {...register('password')} className={`form-control ${errors.password ? 'is-invalid' : ''}`} />
          <div className="invalid-feedback">{errors.password?.message}</div>
        </div>
        <div className="form-group col">
          <label>Confirm Password</label>
          <input name="confirmPassword" type="password" {...register('confirmPassword')} className={`form-control ${errors.confirmPassword ? 'is-invalid' : ''}`} />
          <div className="invalid-feedback">{errors.confirmPassword?.message}</div>
        </div>
      </div>
      <div className="form-group">

        {isDirty && <strong style={{ color: 'red' }}>変更されました</strong>}

        <button type="submit" disabled={formState.isSubmitting} className="btn btn-primary mr-2">
          {formState.isSubmitting && <span className="spinner-border spinner-border-sm mr-1"></span>}
          Save
        </button>
        <button onClick={() => reset(formOptions.defaultValues)} type="button" disabled={formState.isSubmitting} className="btn btn-secondary">Reset</button>
        <Link href="/users" className="btn btn-link">Cancel</Link>
      </div>
    </form>
  );
}