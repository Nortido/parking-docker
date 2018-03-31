<?php
/**
 * @author Evgeny Novoselov <nortido@gmail.com>
 */
namespace App\Tests;

use App\DomainCounter;
use App\Email;
use App\Exceptions\DieException;
use App\FileReader;
use PHPUnit\Exception;
use PHPUnit\Framework\TestCase;
use TypeError;

class Test extends TestCase
{
    function test_file_exist()
    {
        $result = FileReader::read_file("emails.txt" );

        $this->assertTrue( is_array( $result ) );
    }

    function test_file_not_exist()
    {
        $this->expectException( DieException::class );
        $this->expectExceptionMessageRegExp( "/^Can't read file/" );

        $result = FileReader::read_file("emails2.txt" );
    }

    function test_empty_filename()
    {
        $this->expectException( DieException::class );
        $this->expectExceptionMessage( "Enter file name" );

        $result = FileReader::read_file("");
    }
    function test_empty_file()
    {
        $this->expectException( DieException::class );
        $this->expectExceptionMessage( "File is empty" );

        $result = FileReader::read_file("empty.txt" );
    }

    function test_email_valid()
    {
        $result = Email::email_validate("email@email.com" );

        $this->assertTrue( $result );
    }

    function test_email_name_is_short()
    {
        $result = Email::email_validate("l@email.com" );

        $this->assertNotTrue( $result );
    }

    function test_email_name_not_valid()
    {
        $result = Email::email_validate("!#*(^$#(^!@email.com" );

        $this->assertNotTrue( $result );
    }

    function test_email_name_is_empty()
    {
        $result = Email::email_validate("@email.com" );

        $this->assertNotTrue( $result );
    }

    function test_email_domain_without_zone()
    {
        $result = Email::email_validate("email@email." );

        $this->assertNotTrue( $result );

        $result = Email::email_validate("email@email" );

        $this->assertNotTrue( $result );
    }

    function test_email_domain_without_special_character()
    {
        $result = Email::email_validate("emailemail.com" );

        $this->assertNotTrue( $result );

        $result = Email::email_validate("emailemailcom" );

        $this->assertNotTrue( $result );
    }

    function test_email_empty()
    {
        $result = Email::email_validate("" );

        $this->assertNotTrue( $result );
    }

    function test_domain()
    {
        $result = Email::get_domain("email@email.com" );

        $this->assertEquals("email.com", $result);
    }

    function test_domain_too_short()
    {
        $result = Email::get_domain("email@email.c" );

        $this->assertEquals("INVALID", $result);
    }

    function test_processing()
    {
        $result = ( new DomainCounter() )->process_all_emails( [ 'email@email.com', 'email2@mail.ru' ] );

        $this->assertArrayHasKey("email.com", $result);
        $this->assertArrayHasKey("mail.ru", $result);
        $this->assertEquals( $result[ "mail.ru" ], 1);
        $this->assertEquals( $result[ "email.com" ], 1);
    }

    function test_processing_not_array()
    {
        $this->expectException( TypeError::class );

        $result = ( new DomainCounter() )->process_all_emails( 'email@email.com' );
    }

    function test_processing_empty_array()
    {
        $this->expectException( DieException::class );
        $this->expectExceptionMessage( "No emails to process" );

        $result = ( new DomainCounter() )->process_all_emails( [] );
    }

    function test_showing_emails_info_not_array()
    {
        $this->expectException( TypeError::class );

        $result = ( new DomainCounter() )->show_emails_info( 'vk.com' );
    }

    function test_showing_empty_array()
    {
        $this->expectException( DieException::class );
        $this->expectExceptionMessage( "No emails to process" );

        $result = ( new DomainCounter() )->show_emails_info( [] );
    }
}
