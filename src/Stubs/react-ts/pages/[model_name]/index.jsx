import { useState, useEffect } from 'react';
import { Link } from 'components/Link';
import { userService } from 'services/userService';

import crudConfig from 'user.yml';


export default Index;

function Index() {

  const [users, setUsers] = useState(null);

  useEffect(() => {
    userService.getAll().then(x => setUsers(x));
  }, []);

  function deleteUser(id) {
    setUsers(users.map(x => {
      if (x.id === id) { x.isDeleting = true; }
      return x;
    }));
    userService.delete(id).then(() => {
      setUsers(users => users.filter(x => x.id !== id));
    });
  }

  return (
    <div>
      <h1>Users</h1>
      <Link href="/users/add" className="btn btn-sm btn-success mb-2">Add User</Link>
      <table className="table table-striped">
        <thead>
          <tr>
            {Object.keys(crudConfig.table_desc).map(key => {
              if (crudConfig.table_desc[key]['view_list_flag'] == 1)
                return <th className="notsortable">{crudConfig.table_desc[key]['view_list_title']}</th>
            })}
            <th>{/* edit */}</th>
          </tr>
        </thead>
        <tbody>
          {users && users.map(user =>
            <tr key={user.id}>
              {Object.keys(crudConfig.table_desc).map(key => {
                if (crudConfig.table_desc[key]['view_list_flag'] == 1)
                  return <td>{user[key]}</td>
              })}
              <td style={{ whiteSpace: 'nowrap' }}>
                <Link href={`/users/${user.id}`} className="btn btn-sm btn-primary mr-1">Edit</Link>
                <button onClick={() => deleteUser(user.id)} className="btn btn-sm btn-danger btn-delete-user" disabled={user.isDeleting}>
                  {user.isDeleting
                    ? <span className="spinner-border spinner-border-sm"></span>
                    : <span>Delete</span>
                  }
                </button>
              </td>

            </tr>
          )}
          {!users &&
            <tr>
              <td colSpan="4" className="text-center">
                <div className="spinner-border spinner-border-lg align-center"></div>
              </td>
            </tr>
          }
          {users && !users.length &&
            <tr>
              <td colSpan="4" className="text-center">
                <div className="p-2">No Users To Display</div>
              </td>
            </tr>
          }
        </tbody>
      </table>
    </div>
  );
}
