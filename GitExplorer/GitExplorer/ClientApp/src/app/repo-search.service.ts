import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { Repo } from './model/repo';
import { HttpHeaders, HttpClient } from '@angular/common/http'
import { Commit } from './model/commit'
import { RepoSearchResults } from './model/repo-search-results'
import { Issue } from './model/issue';

@Injectable({
  providedIn: 'root'
})
export class RepoSearchService {

  apiUrl: string = 'https://api.github.com'
  auth: string = 'token 68ac832bf56f65c0f89d23b67fef3b7e9f4200e8'

  header = {
    headers: new HttpHeaders({ 'Authorization': this.auth })
  }

  constructor(private http: HttpClient) { }

  searchForRepos(searchString: string, page: number): Observable<RepoSearchResults> {
    var endpoint = `${this.apiUrl}/search/repositories?q=${searchString}&page=${page}&per_page=10`
    return this.http.get<RepoSearchResults>(endpoint, this.header)
  }

  getRepo(full_name: string): Observable<Repo> {
    var endpoint = `${this.apiUrl}/repos/${full_name}`
    return this.http.get<Repo>(endpoint, this.header)
  }

  getCommits(full_name: string): Observable<Commit[]> {
    var endpoint = `${this.apiUrl}/repos/${full_name}/commits`
    return this.http.get<Commit[]>(endpoint, this.header)
  }

  getUserRepos(user: string): Observable<Repo[]> {
    var endpoint = `${this.apiUrl}/users/${user}/repos`
    return this.http.get<Repo[]>(endpoint, this.header)
  }

  getIssues(full_name: string, page: number): Observable<Issue[]> {
    var endpoint = `${this.apiUrl}/repos/${full_name}/issues?page=${page}&per_page=10`
    return this.http.get<Issue[]>(endpoint, this.header)
  }
}
