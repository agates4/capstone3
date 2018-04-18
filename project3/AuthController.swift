//
//  AuthController.swift
//  project3
//
//  Created by Aron Gates on 3/22/18.
//  Copyright © 2018 Aron Gates. All rights reserved.
//

//
//  ViewController.swift
//  project3
//
//  Created by Aron Gates on 1/24/18.
//  Copyright © 2018 Aron Gates. All rights reserved.
//

import UIKit
import Alamofire

class AuthController: UIViewController  {
    
    var loginVisible = true
    
    @IBOutlet weak var loginEmail: UITextField!
    @IBOutlet weak var loginPassword: UITextField!
    @IBOutlet weak var loginView: UIView!
    
    @IBAction func login(_ sender: UIButton) {
        let urlString = "http://capstone3.herokuapp.com/login.php/"
        
        if (loginEmail.text != nil && loginPassword.text != nil) {
            let email = loginEmail.text!
            let password = loginPassword.text!
            print(email,password)
            
            let parameters: [String: AnyObject] = [
                "email" : email as AnyObject,
                "password" : password as AnyObject,
            ]
            
            // test username: agates10@kent.edu
            // test password: helllo
            
            Alamofire.request(urlString, method: .post, parameters: parameters, encoding: URLEncoding.httpBody).responseJSON {
                response in
                print(response)
                print(response.data)
                print(response.description)
                print(response.debugDescription)
                
                switch response.result {
                case .success:
                    print(response)
                    break
                case .failure(let error):
                    print("poopy")
                    print(error)
                }
            }
        }
        
        
        performSegue(withIdentifier: "tolist", sender: nil)
    }
    
    @IBOutlet weak var registerUsername: UITextField!
    @IBOutlet weak var registerEmail: UITextField!
    @IBOutlet weak var registerPassword: UITextField!
    @IBOutlet weak var registerView: UIView!
    
    @IBAction func register(_ sender: UIButton) {
        
    }
    
    @IBOutlet weak var switchButton: UIButton!
    @IBAction func switchViews(_ sender: UIButton) {
        if (loginVisible) {
            view.bringSubview(toFront: registerView)
            switchButton.setTitle("Have an account? Click here", for: .normal)
        } else {
            view.bringSubview(toFront: loginView)
            switchButton.setTitle("Need an account? Click here", for: .normal)
        }
        loginVisible = !loginVisible
    }
    
    override func prepare(for segue: UIStoryboardSegue, sender: Any?) {
        let backItem = UIBarButtonItem()
        backItem.title = "Log Out"
        navigationItem.backBarButtonItem = backItem
    }
    
}

