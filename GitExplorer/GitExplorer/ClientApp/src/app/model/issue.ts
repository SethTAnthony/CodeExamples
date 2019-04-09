import { User } from "./user";

export class Issue {
  "id": number
  "node_id": string
  "url": string
  "repository_url": string
  "labels_url": string
  "comments_url": string
  "events_url": string
  "html_url": string 
  "number": number
  "title": string
  "user": User
  "labels": {
    id: number
    node_id: string
    url: string
    name: string
    description: string
    color: string
    default: boolean
  }
  "state": string
  "locked": boolean
  "assignee": User
  "assignees": User
  "milestone": any
  "comments": number
  "created_at": Date
  "updated_at": Date
  "closed_at": Date
  "author_association": string
  "body": string
  }
