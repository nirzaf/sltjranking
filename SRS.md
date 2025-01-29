# Software Requirements Specification (SRS) for SLTJ Ranking Management System

## 1. Introduction

### 1.1 Purpose
The purpose of this document is to provide a detailed Software Requirements Specification (SRS) for the SLTJ Ranking Management System. This document outlines the system's functionality, features, and requirements to ensure a clear understanding of the system's objectives and scope.

### 1.2 Scope
The SLTJ Ranking Management System is a web-based application designed to manage the ranking system of Sri Lanka Thawheedh Jamath (SLTJ). The system allows administrators to manage events, users, and rankings efficiently. It provides a platform for branches to register events, track their progress, and view their rankings.

### 1.3 Definitions, Acronyms, and Abbreviations
- SLTJ: Sri Lanka Thawheedh Jamath
- SRS: Software Requirements Specification
- Admin: Administrator
- User: Registered user of the system

### 1.4 References
- Project README.md file
- Database schema (library.sql)

### 1.5 Overview
This document is organized into several sections, including an introduction, overall description, system features, external interface requirements, system requirements, and other nonfunctional requirements.

## 2. Overall Description

### 2.1 Product Perspective
The SLTJ Ranking Management System is a standalone web application that provides a platform for managing events, users, and rankings. It is designed to be user-friendly and accessible on various devices.

### 2.2 Product Functions
- Event Management: Add, edit, and delete events.
- User Management: Manage user accounts and their roles.
- Ranking Management: Calculate and display rankings based on event participation and points.
- Dashboard: View summary statistics and rankings.
- Authentication: Secure login for users and administrators.
- Responsive Design: Accessible on various devices.

### 2.3 User Classes and Characteristics
- Administrators: Users with full access to manage events, users, and rankings.
- Branch Users: Users with access to register events and view their rankings.

### 2.4 Operating Environment
The system is a web-based application that can be accessed using modern web browsers such as Google Chrome, Mozilla Firefox, and Microsoft Edge.

### 2.5 Design and Implementation Constraints
- The system must be developed using PHP and MySQL.
- The system must be responsive and accessible on various devices.

### 2.6 Assumptions and Dependencies
- Users have access to the internet and a modern web browser.
- The system relies on a MySQL database for data storage.

## 3. System Features

### 3.1 Event Management
#### 3.1.1 Description and Priority
The Event Management feature allows administrators to add, edit, and delete events. This feature is of high priority as it is essential for managing the ranking system.

#### 3.1.2 Functional Requirements
- The system shall allow administrators to add new events.
- The system shall allow administrators to edit existing events.
- The system shall allow administrators to delete events.

### 3.2 User Management
#### 3.2.1 Description and Priority
The User Management feature allows administrators to manage user accounts and their roles. This feature is of high priority as it is essential for controlling access to the system.

#### 3.2.2 Functional Requirements
- The system shall allow administrators to add new users.
- The system shall allow administrators to edit user information.
- The system shall allow administrators to delete users.
- The system shall allow administrators to assign roles to users.

### 3.3 Ranking Management
#### 3.3.1 Description and Priority
The Ranking Management feature calculates and displays rankings based on event participation and points. This feature is of high priority as it is the core functionality of the system.

#### 3.3.2 Functional Requirements
- The system shall calculate rankings based on event participation and points.
- The system shall display rankings in a user-friendly format.
- The system shall allow users to view their rankings.

### 3.4 Dashboard
#### 3.4.1 Description and Priority
The Dashboard feature provides a summary of statistics and rankings. This feature is of medium priority as it enhances the user experience.

#### 3.4.2 Functional Requirements
- The system shall display a summary of statistics on the dashboard.
- The system shall display rankings on the dashboard.

### 3.5 Authentication
#### 3.5.1 Description and Priority
The Authentication feature provides secure login for users and administrators. This feature is of high priority as it ensures the security of the system.

#### 3.5.2 Functional Requirements
- The system shall provide a login page for users and administrators.
- The system shall authenticate users and administrators based on their credentials.
- The system shall provide a logout option for users and administrators.

### 3.6 Responsive Design
#### 3.6.1 Description and Priority
The Responsive Design feature ensures that the system is accessible on various devices. This feature is of medium priority as it enhances the user experience.

#### 3.6.2 Functional Requirements
- The system shall be accessible on various devices, including desktops, tablets, and smartphones.
- The system shall adjust its layout based on the device's screen size.

## 4. External Interface Requirements

### 4.1 User Interfaces
- The system shall provide a user-friendly interface for managing events, users, and rankings.
- The system shall provide a responsive design that adjusts to different screen sizes.

### 4.2 Hardware Interfaces
- The system shall be accessible on devices with internet access and a modern web browser.

### 4.3 Software Interfaces
- The system shall interact with a MySQL database for data storage.

### 4.4 Communication Interfaces
- The system shall use HTTPS for secure communication between the client and server.

## 5. System Requirements

### 5.1 Functional Requirements
- The system shall provide event management functionality.
- The system shall provide user management functionality.
- The system shall provide ranking management functionality.
- The system shall provide a dashboard with summary statistics and rankings.
- The system shall provide authentication for users and administrators.
- The system shall be responsive and accessible on various devices.

### 5.2 Nonfunctional Requirements
- The system shall be developed using PHP and MySQL.
- The system shall be secure and protect user data.
- The system shall be user-friendly and easy to navigate.
- The system shall be responsive and accessible on various devices.

## 6. Other Nonfunctional Requirements

### 6.1 Performance Requirements
- The system shall respond to user actions within 2 seconds.
- The system shall handle up to 100 concurrent users.

### 6.2 Security Requirements
- The system shall use HTTPS for secure communication.
- The system shall encrypt user passwords.
- The system shall implement role-based access control.

### 6.3 Usability Requirements
- The system shall provide a user-friendly interface.
- The system shall provide clear and concise instructions for users.

### 6.4 Reliability Requirements
- The system shall have an uptime of 99.9%.
- The system shall provide error messages for any issues encountered.

### 6.5 Maintainability Requirements
- The system shall be developed using modular code to facilitate maintenance.
- The system shall provide documentation for developers.

### 6.6 Portability Requirements
- The system shall be accessible on various devices, including desktops, tablets, and smartphones.
- The system shall be compatible with modern web browsers.
