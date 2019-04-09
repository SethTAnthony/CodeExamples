import { Injectable } from '@angular/core'
import { HttpClient, HttpHeaders } from '@angular/common/http'
import { UserSearchResults } from './model/user-search-results'
import { Observable } from 'rxjs'
import { User } from './model/user'

@Injectable({
  providedIn: 'root'
})
export class SearchService {

  apiUrl: string = 'https://api.github.com';
  auth: string = 'token 68ac832bf56f65c0f89d23b67fef3b7e9f4200e8'

  header = {
    headers: new HttpHeaders({ 'Authorization': this.auth })
  }

  constructor(private http: HttpClient) { }

  searchForUsers(searchString: string, page: number): Observable<UserSearchResults> {
    var endpoint = `${this.apiUrl}/search/users?q=${searchString}&page=${page}&per_page=10`
    return this.http.get<UserSearchResults>(endpoint, this.header)
  }

  getUser(id: string): Observable<User> {
    var endpoint = `${this.apiUrl}/users/${id}`
    return this.http.get<User>(endpoint, this.header)
  }

  getFollowers(user: string): Observable<User[]> {
    var endpoint = `${this.apiUrl}/users/${user}/followers`
    return this.http.get<User[]>(endpoint, this.header)
  }
}
