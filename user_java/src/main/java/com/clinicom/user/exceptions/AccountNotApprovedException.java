package com.clinicom.user.exceptions;

public class AccountNotApprovedException extends Exception {
    public AccountNotApprovedException(String message) {
        super(message);
    }
}