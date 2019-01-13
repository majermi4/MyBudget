# MyBudget

[![Build Status](https://travis-ci.org/majermi4/MyBudget.svg?branch=master)](https://travis-ci.org/majermi4/MyBudget)

A hobby App for keeping track of household expenses. 

I made this app with the intention of actually using it but also to learn, practice and demonstrate concepts such as Event sourcing/CQRS, Domain Driven Design, Behavioral testing, etc. 

TODO:
- Add more tests (domain & API)
- Add fulltext search capabilities
- Add projection/domain serivce that will compute who should pay next
- Add controller actions based on the needs of the client mobile app
- Deploy to AWS, create an easy CI pipeline. Consider using a json storage hosting solution as permanent data storage.
- Improve "Money" value object implementation