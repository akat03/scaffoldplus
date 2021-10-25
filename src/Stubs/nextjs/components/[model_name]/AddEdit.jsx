import { useRouter } from 'next/router';
import { useState } from 'react';
import { useForm, useFormState } from 'react-hook-form';
import { yupResolver } from '@hookform/resolvers/yup';
import * as Yup from 'yup';

import { Link } from 'components/Link';
import { userService } from '/services/userService';
import { alertService } from '/services/alertService';

import { getStyleObjectFromString } from "helpers/stringHelper";


export { AddEdit };

function AddEdit(props) {
  const user = props?.user;
  const isAddMode = !user;
  const router = useRouter();
  const [showPassword, setShowPassword] = useState(false);

  // form validation rules
  const validationSchema = Yup.object().shape({
    // name: Yup.string()
    //   .required('名前は入力必須です'),
    title: Yup.string()
      .required('Title is required'),
    // firstName: Yup.string()
    //   .required('First Name is required'),
    // lastName: Yup.string()
    //   .required('Last Name is required'),
    email: Yup.string()
      .email('Email is invalid')
      .required('Email is required'),
    // role: Yup.string()
    //   .required('Role is required'),
    // password: Yup.string()
    //   .transform(x => x === '' ? undefined : x)
    //   .concat(isAddMode ? Yup.string().required('Password is required') : null)
    //   .min(6, 'Password must be at least 6 characters'),
    // confirmPassword: Yup.string()
    //   .transform(x => x === '' ? undefined : x)
    //   .when('password', (password, schema) => {
    //     if (password || isAddMode) return schema.required('Confirm Password is required');
    //   })
    //   .oneOf([Yup.ref('password')], 'Passwords must match')
  });
  const formOptions = { resolver: yupResolver(validationSchema) };

  // set default form values if in edit mode
  if (!isAddMode) {
    const { password, confirmPassword, ...defaultValues } = user;
    formOptions.defaultValues = defaultValues;
  }

  // get functions to build form with useForm() hook
  const { register, handleSubmit, reset, formState } = useForm(formOptions);
  const { errors } = formState;


  function doSubmitForm(data) {
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
    if (errors[form_name]) {
      return ' is-invalid';
    }
    else if (formState.dirtyFields[form_name]) {
      return ' is-valid';
    }
    else {
      return '';
    }
  }


  return (
    <form onSubmit={handleSubmit(doSubmitForm)}>
      <h1>{isAddMode ? 'Add User' : 'Edit User'}</h1>

      <div className="form-row">
        <div className="form-group col">
          <label>
            タイトル
            <span style={{ fontSize: '95%', paddingLeft: '15px', opacity: '0.5' }}>( name )</span>
          </label>
          <input type="text" {...register('title')} className={'form-control' + getValidOrInvalidClassname('title')}
            style={getStyleObjectFromString('font-size:14px; font-weight:bold;')}
          />
          <div className="invalid-feedback">{errors.title?.message}</div>
        </div>
      </div>

      <div className="form-row">
        <div className="form-group col">
          <label>
            メールアドレス
            <span style={{ fontSize: '95%', paddingLeft: '15px', opacity: '0.5' }}>( email )</span>
          </label>
          <input type="text" {...register('email')} className={'form-control' + getValidOrInvalidClassname('email')}
            style={getStyleObjectFromString('font-size:14px; font-weight:bold;')}
          />
          <div className="invalid-feedback">{errors.email?.message}</div>
        </div>
      </div>

      <button type="submit" disabled={formState.isSubmitting} className="btn btn-primary mr-2">
        {formState.isSubmitting && <span className="spinner-border spinner-border-sm mr-1"></span>}
        Save
      </button>
      <button onClick={() => reset(formOptions.defaultValues)} type="button" disabled={formState.isSubmitting} className="btn btn-secondary">Reset</button>
      <Link href="/users" className="btn btn-link">Cancel</Link>
    </form>
  );
}