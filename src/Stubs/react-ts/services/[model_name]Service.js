// import { apiUrl } from 'config';
import { fetchWrapper } from 'helpers/fetch-wrapper';

export const userService = {
    getAll,
    getById,
    create,
    update,
    delete: _delete
};


const baseUrl = process.env.NEXT_PUBLIC_API_URL + '/users';

// const baseUrl = `${apiUrl}/users`;
console.log( '● baseUrl' );
console.log( baseUrl );


function getAll() {
    return fetchWrapper.get(baseUrl);
}

function getById(id) {
    return fetchWrapper.get(`${baseUrl}/${id}`);
}

function create(params) {
    return fetchWrapper.post(baseUrl, params);
}

function update(id, params) {
    return fetchWrapper.put(`${baseUrl}/${id}`, params);
}

// prefixed with underscored because delete is a reserved word in javascript
function _delete(id) {
    return fetchWrapper.delete(`${baseUrl}/${id}`);
}
